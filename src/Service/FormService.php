<?php

namespace App\Service;

use App\Entity\Form;
use App\DTO\Form as ImmutableFormInput;
use App\Entity\Form as FormEntity;
use App\Entity\FormItem;
use App\Entity\FormValues;
use App\Entity\UserForms;
use App\Lib\DesiredValue;
use App\Lib\FormValue;
use App\Repository\FormItemRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\InvalidArgumentException;
use stdClass;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Throwable;

class FormService
{
    public const FORM_NAME = 'name';
    public const FORM_DESCRIPTION = 'description';

    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly CacheInterface $cache
    ) {

    }

    public function create(ImmutableFormInput $formInput): ?FormEntity
    {
        dump($formInput);
        $form =  (new FormEntity())
            ->setName($formInput->getName())
            ->setDescription($formInput->getDescription());

        foreach ($formInput->getFormItems() as $formItem) {
            $form->addFormItem($formItem);
        }
        $this->registry->getManager()->persist($form);
        try {
            $this->registry->getManager()->flush();
        } catch (Throwable) {
            return null;
        }
        return $form;
    }

    public function getById(int $id): ?Form
    {
        $form = $this->registry->getRepository(FormEntity::class)->find($id);
        if($form === null) {
            return null;
        }
        return $form;
    }

    public function getFormsForUser(UserInterface $user, bool $shortVersion = false): array
    {
        $userForms = $this->registry->getRepository(UserForms::class)->findOneBy([
            'user' => $user
        ]);

        if($shortVersion) {
            return array_map(static function (FormEntity $form) {
                return [
                    'name' => $form->getName(),
                    'id' => $form->getId(),
                    'description' => $form->getDescription(),
                    'date' => $form->getUpdatedAt(),
                    'weight' => $this->calculateWeight($form)
                ];
            }, $userForms->getForms());
        }
        return $userForms->getForms();
    }

    public function calculateWeight(FormEntity $form): float|int
    {
        $weights = array_map(static function(FormItem $item) {
            foreach($item->getParameterValues() as $parameter) {
                if($parameter->getParameter()->getName() === 'weight') {
                    return floatval($parameter->getValue());
                }
            }
            return 0;
        }, $form->getFormItems()->toArray());
        return array_sum($weights);
    }

    public function saveValues(int $formId, array $data, UserInterface $user): bool
    {
        $form = $this->registry->getRepository(Form::class)->find($formId);
        if(!$form) {
            return false;
        }
        $formValue = (new FormValues())->setForm($form)->setUser($user);
        /** @var FormItemRepository $formItemRepository */
        $formItemRepository = $this->registry->getRepository(FormItem::class);
        foreach($data as $value) {
            $formValue->addValue(
                (new FormValue($formItemRepository))
                    ->setFormItemId($value->itemId)
                    ->setFormItemValue($value->answer)
            );
        }
        $this->registry->getManager()->persist($formValue);
        try {
            $this->registry->getManager()->flush();
        } catch (Throwable) {
            return false;
        }
        return true;
    }

    public function getFormValues(int $formId): int|array {
        $form = $this->registry->getRepository(Form::class)->find($formId);
        if(!$form) {
            return Response::HTTP_NOT_FOUND;
        }
        return $this->registry->getRepository(FormValues::class)->findBy([
            'form' => $form
        ]);
    }

    public function getFormStats(int $formId): int|array {
        $form = $this->registry->getRepository(Form::class)->find($formId);
        if(!$form) {
            return Response::HTTP_NOT_FOUND;
        }
        $responses =  $this->registry->getRepository(FormValues::class)->findBy([
            'form' => $form
        ]);
        $statistics = [
            'numberOfResponses' => count($responses)
        ];
        $weights = [];
        foreach($responses as $response) {
            ['grade' => $grades, 'weight' => $weight] = $this->getResponseData($form, $response->getValues());
            $formGrades = [
                'formSubmissionId' => $response->getId(),
                'username' => $response->getUser()->getUsername(),
                'grade' => $grades
            ];
            $weights[] = $weight;
            $statistics['grades'][] = $formGrades;
        }
        $statistics['weights'] = $this->getWeightStatistics($weights);
        return $statistics;
    }

    /**
     * @param FormValue[] $values
     * @return array
     * @throws InvalidArgumentException
     */
    private function getResponseData(array $values): array {
        $grade = 0;
        $weight = 0;
        foreach($values as $value) {
            $defaultValue = $this->getItemDefaults($value);
            if($defaultValue->isWeight()) {
                $grade += $defaultValue->getValue();
                $weight += $defaultValue->getValue();
                continue;
            }
            if($defaultValue->getValue() == $value->getFormItemValue()) {
                $grade++;
            }
        }
        return ['grade' => $grade, 'weight' => $weight];
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getItemDefaults(FormItem $formItem): DesiredValue {
        return $this->cache->get('form_item_'.$formItem->getId(), function(CacheItem $item) use ($formItem){
            $item->expiresAfter(time: 3600);
            if(!$formItem->getParameterValues()->isEmpty()) {
                foreach($formItem->getParameterValues() as $parameterValue) {
                    if($parameterValue->getParameter()->getName() === 'weight') {
                        return new DesiredValue(true, $parameterValue->getValue());
                    }
                }
            }
            return new DesiredValue(false, $formItem->getDesiredValue());
        });
    }

    private function getWeightStatistics(array $weights): array {
        $maxWeight = max($weights);
        $statistic = [];
        for($currentWeight = 1; $currentWeight <= $maxWeight; $currentWeight++) {
            foreach($weights as $weight) {
                if ($weight !== $currentWeight) {
                    continue;
                }
                if(!isset($statistic[$currentWeight])) {
                    $statistic[$currentWeight] = 0;
                }
                $statistic[$currentWeight]++;
            }
        }
        return $statistic;
    }

}
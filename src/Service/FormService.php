<?php

namespace App\Service;

use App\DTO\Form as ImmutableFormInput;
use App\Entity\Form as FormEntity;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

class FormService
{
    public const FORM_NAME = 'name';
    public const FORM_DESCRIPTION = 'description';

    public function __construct(
        private readonly ManagerRegistry $registry
    ) {

    }

    public function create(ImmutableFormInput $formInput): ?FormEntity
    {
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

    public function getById(int $id): ?FormEntity
    {
        return $this->registry->getRepository(FormEntity::class)->find($id);
    }
}
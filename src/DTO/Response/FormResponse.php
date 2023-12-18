<?php

namespace App\DTO\Response;

use App\Entity\Form;
use App\Entity\FormItem;
use App\Entity\FormWidgetParameterValue;
use DateTimeImmutable;
use stdClass;

class FormResponse
{

    private static ?FormResponse $instance = null;

    protected function __construct(
        private readonly ?int $id,
        private readonly ?string $name,
        private readonly ?string $description,
        private readonly ?DateTimeImmutable $updatedAt,
        private readonly ?array $items,
        private readonly float $totalWeight
    ) {
    }

    public static function create(Form $form): FormResponse {
        $items = [];
        $totalWeight = 0;
        /** @var FormItem $formItem */
        foreach ($form->getFormItems() as $formItem) {
            $item = new stdClass;
            $item->question = $formItem->getName();
            $item->description = $formItem->getDescription();
            $item->type = $formItem->getFormWidget()->getFormType()->value;
            $item->answer = null;
            $item->isRequired = null;
            $item->weight = null;
            $parameters = $formItem->getParameterValues();
            foreach ($parameters as $parameter) {
                $name = $parameter->getParameter()->getName();
                if ($name === 'isRequired') {
                    $item->$name = (bool) $parameter->getValue();
                    continue;
                }
                if ($name === 'weight') {
                    $item->$name = (float) $parameter->getValue();
                    $totalWeight += $parameter->getValue();
                    continue;
                }
                $item->$name = $parameter->getValue();
            }
            $items[] = $item;
        }
        if (self::$instance === null) {
            self::$instance = new static(
                $form->getId(),
                $form->getName(),
                $form->getDescription(),
                $form->getUpdatedAt(),
                $items,
                $totalWeight
            );
        }
        return self::$instance;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return array|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @return float
     */
    public function getTotalWeight(): float
    {
        return $this->totalWeight;
    }
}
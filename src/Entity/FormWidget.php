<?php

namespace App\Entity;

use App\Lib\FormTypes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FormWidget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER, enumType: FormTypes::class)]
    private FormTypes $formType;

    #[ORM\Column(type: Types::STRING)]
    private string $uiComponentName;

    public function getId(): int {
        return $this->id;
    }

    public function getFormType(): FormTypes
    {
        return $this->formType;
    }

    public function setFormType(FormTypes $formType): FormWidget
    {
        $this->formType = $formType;
        return $this;
    }

    public function getUiComponentName(): string
    {
        return $this->uiComponentName;
    }

    public function setUiComponentName(string $uiComponentName): FormWidget
    {
        $this->uiComponentName = $uiComponentName;
        return $this;
    }
}
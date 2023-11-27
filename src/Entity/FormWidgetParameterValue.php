<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FormWidgetParameterValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: FormWidgetParameter::class)]
    private FormWidgetParameter $option;

    #[ORM\Column(Types::STRING)]
    private string $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): FormWidgetParameterValue
    {
        $this->id = $id;
        return $this;
    }

    public function getOption(): FormWidgetParameter
    {
        return $this->option;
    }

    public function setOption(FormWidgetParameter $option): FormWidgetParameterValue
    {
        $this->option = $option;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): FormWidgetParameterValue
    {
        $this->value = $value;
        return $this;
    }
}
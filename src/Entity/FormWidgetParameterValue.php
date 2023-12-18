<?php

namespace App\Entity;

use App\Repository\FormWidgetParameterValueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormWidgetParameterValueRepository::class)]
class FormWidgetParameterValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: FormWidgetParameter::class)]
    private FormWidgetParameter $parameter;

    #[ORM\Column(Types::STRING, nullable: true)]
    private ?string $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): FormWidgetParameterValue
    {
        $this->id = $id;
        return $this;
    }

    public function getParameter(): FormWidgetParameter
    {
        return $this->parameter;
    }

    public function setParameter(FormWidgetParameter $parameter): FormWidgetParameterValue
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(?string $value): FormWidgetParameterValue
    {
        $this->value = $value;
        return $this;
    }
}
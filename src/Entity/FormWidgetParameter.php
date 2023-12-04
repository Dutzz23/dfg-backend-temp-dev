<?php

namespace App\Entity;

use App\Repository\FormWidgetParameterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormWidgetParameterRepository::class)]
class FormWidgetParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(Types::STRING)]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): FormWidgetParameter
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): FormWidgetParameter
    {
        $this->name = $name;
        return $this;
    }
}
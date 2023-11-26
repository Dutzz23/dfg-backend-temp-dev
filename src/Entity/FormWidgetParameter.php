<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FormWidgetOption
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

    public function setId(int $id): FormWidgetOption
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): FormWidgetOption
    {
        $this->name = $name;
        return $this;
    }
}
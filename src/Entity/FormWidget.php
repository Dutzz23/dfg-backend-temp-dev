<?php

namespace App\Entity;

use App\Lib\FormTypes;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FormItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::BIGINT)]
    private int $formItemId;

    #[ORM\Column(type: 'int', enumType: FormTypes::class)]
    private FormTypes $formType;

    private string $name;

    private string $description;

    private bool $isRequired;

    
}
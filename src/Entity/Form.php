<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\ManyToMany(targetEntity: FormItem::class)]
    #[ORM\JoinTable(name: 'form_items')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'item_id', referencedColumnName: 'id')]
    private Collection $formItems;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $description;

    public function __construct() {
        $this->formItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Form
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Form
    {
        $this->name = $name;
        return $this;
    }

    public function getFormItems(): Collection
    {
        return $this->formItems;
    }

    public function addFormItem(FormItem $item) {
        if(!$this->formItems->contains($item)) {
            $this->formItems->add($item);
        }
        return $this;
    }

    public function removeFormItem(FormItem $item) {
        if($this->formItems->contains($item)) {
            $this->formItems->removeElement($item);
        }
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): Form {
        $this->description = $description;
        return $this;
    }
}
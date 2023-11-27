<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FormItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: FormWidget::class)]
    private FormWidget $formWidget;

    private string $name;

    private string $description;

    #[ORM\ManyToMany(targetEntity: FormWidgetParameterValue::class)]
    #[ORM\JoinTable(name: 'form_items_parameter_values')]
    #[ORM\JoinColumn(name: 'form_item_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'parameter_value_id', referencedColumnName: 'id')]
    private Collection $parameterValues;

    public function __construct() {
        $this->parameterValues = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): FormItem
    {
        $this->id = $id;
        return $this;
    }

    public function getFormWidget(): FormWidget
    {
        return $this->formWidget;
    }

    public function setFormWidget(FormWidget $formWidget): FormItem
    {
        $this->formWidget = $formWidget;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): FormItem
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): FormItem
    {
        $this->description = $description;
        return $this;
    }

    public function getParameterValues(): Collection
    {
        return $this->parameterValues;
    }

    public function addParameterValue(FormWidgetParameterValue $value) {
        if(!$this->parameterValues->contains($value)) {
            $this->parameterValues->add($value);
        }
        return $this;
    }

    public function removeParameterValue(FormWidgetParameterValue $value) {
        if($this->parameterValues->contains($value)) {
            $this->parameterValues->removeElement($value);
        }
        return $this;
    }
}
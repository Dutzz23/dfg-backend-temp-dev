<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserForms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\OneToOne]
    private User $user;

    #[ORM\ManyToMany(targetEntity: FormValues::class)]
    #[ORM\JoinTable(name: 'user_form_values')]
    #[ORM\JoinColumn(name: 'user_form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_values_id', referencedColumnName: 'id')]
    private Collection $forms;

    public function __construct()
    {
        $this->forms = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserForms
     */
    public function setUser(User $user): UserForms
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getForms(): Collection
    {
        return $this->forms;
    }

    public function addForm(FormValues $formValues)
    {
        if (!$this->forms->contains($formValues)) {
            $this->forms->add($formValues);
        }
        return $this;
    }


    public function removeForm(FormValues $formValues)
    {
        if ($this->forms->contains($formValues)) {
            $this->forms->removeElement($formValues);
        }
        return $this;
    }
}
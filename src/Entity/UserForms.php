<?php

namespace App\Entity;

use DateTimeImmutable;
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
    private null|string|int $id;

    #[ORM\OneToOne]
    private User $user;

    #[ORM\ManyToMany(targetEntity: Form::class)]
    #[ORM\JoinTable(name: 'users_forms')]
    #[ORM\JoinColumn(name: 'user_form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_values_id', referencedColumnName: 'id')]
    private Collection $forms;

    private DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->forms = new ArrayCollection();
        $this->updatedAt = new DateTimeImmutable();
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

    public function addForm(Form $form)
    {
        if (!$this->forms->contains($form)) {
            $this->forms->add($form);
        }
        return $this;
    }


    public function removeForm(Form $form)
    {
        if ($this->forms->contains($form)) {
            $this->forms->removeElement($form);
        }
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): UserForms
    {
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
        return $this;
    }
}
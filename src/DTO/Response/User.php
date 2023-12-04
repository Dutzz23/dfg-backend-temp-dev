<?php

namespace App\DTO\Response;

use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use App\Lib\AbstractIoDTO;
use App\Lib\IoDTO;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User as UserEntity;

class User extends AbstractIoDTO implements IoDTO
{
    protected function __construct(
        private readonly ?int $id,
        private readonly ?string $firstName,
        private readonly ?string $lastName,
        private readonly ?string $email
    )
    {
    }

    public static function create(int|Request $request, ManagerRegistry $registry = null): self {
        if($request instanceof Request) {
            throw new InvalidArgumentException('This method only accepts an id');
        }
        if($registry === null) {
            throw new InvalidArgumentException('Always pass a manager registry instance to this method');
        }

        if(self::$instance === null) {
            $repository = $registry->getRepository(UserEntity::class);
            $user = $repository->find($request);
            self::$instance = new static(
                $user->getId(),
                $user->getUserData()->getFirstName(),
                $user->getUserData()->getLastName(),
                $user->getUserData()->getEmail(),
            );
        }

        return self::$instance;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
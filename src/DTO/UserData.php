<?php

namespace App\DTO;

use App\Lib\AbstractIoDTO;
use App\Lib\IoDTO;
use Doctrine\Persistence\ManagerRegistry;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class UserData extends AbstractIoDTO implements IoDTO
{


    protected function __construct(
        private readonly ?string $firstName,
        private readonly ?string $lastName,
        private readonly ?string $email
    ) {
    }

    public static function create(Request|int $request, ManagerRegistry $registry = null): self
    {
        if (!($request instanceof Request)) {
            throw new InvalidArgumentException('Do not use ids for user data searches');
        }
        return static::getInstance($request);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
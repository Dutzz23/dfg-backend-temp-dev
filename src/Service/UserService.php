<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Throwable;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly ManagerRegistry $registryManager
    ) {
    }

    public function createUser(array $userData): bool
    {
        if (!$this->validateData($userData)) {
            return false;
        }
        if ($this->repository->findOneBy([
                'username' => $userData['username']
            ]) !== null) {
            return false;
        }
        $newUser = (new User())
            ->setUsername($userData['username'])
            ->setPassword($this->passwordHasher->hash($userData['password']));
        $this->registryManager->getManager()->persist($newUser);
        try {
            $this->registryManager->getManager()->flush();
        } catch (Throwable) {
            return false;
        }
        return true;
    }

    private function validateData(array $userData): bool
    {
        if (!array_key_exists('username', $userData)) {
            return false;
        }
        if (!array_key_exists('password', $userData)) {
            return false;
        }
        if (empty($userData['username'])) {
            return false;
        }
        if (empty($userData['password'])) {
            return false;
        }
        return true;
    }
}
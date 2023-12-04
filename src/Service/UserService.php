<?php

namespace App\Service;

use App\Entity\Form;
use App\Entity\FormValues;
use App\Entity\User;
use App\Entity\UserForms;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Throwable;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly ManagerRegistry $registryManager,
        private readonly Security $security
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

    public function getUser(int $id): User|int {
        $user = $this->repository->find($id);
        if($user === null) {
            return 0;
        }
        return $user;
    }

    public function deleteUser(int $userId): bool {
        $user = $this->validateId($userId);
        if ($user === false) {
            return false;
        }
        $this->registryManager->getManager()->remove($user);
        try {
            $this->registryManager->getManager()->flush();
        } catch (Throwable) {
            return false;
        }
        return true;
    }

    private function validateId(int $id): User|false
    {
        if($this->repository->find($id) === null) {
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

    public function attachForm(Form $form): int
    {
        $userForms = $this->registryManager->getRepository(UserForms::class)
            ->findOneBy([
                'user' => $this->security->getUser(),
            ]);
        if($userForms === null) {
            return Response::HTTP_NOT_FOUND;
        }
        $userForms->addForm($form);
        $this->registryManager->getManager()->persist($userForms);
        try {
            $this->registryManager->getManager()->flush();
        } catch (Throwable) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return Response::HTTP_OK;
    }
}
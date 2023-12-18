<?php

namespace App\Service;

use App\Entity\Form;
use App\Entity\User;
use App\Entity\UserData;
use App\Entity\UserForms;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

class UserService
{
    public function __construct(
        private readonly UserRepository              $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ManagerRegistry $registryManager,
        private readonly Security $security
    ) {
    }

    public function createUser(array $registerInput): User|false
    {
        if (!$this->validateData($registerInput)) {
            return false;
        }
        if ($this->repository->findOneBy([
                'username' => $registerInput['username']
            ]) !== null) {
            return false;
        }
        $newUser = (new User())
            ->setUsername($registerInput['username']);

        $this->setUserData($newUser, $registerInput);

        $newUser->setPassword($this->passwordHasher->hashPassword($newUser, $registerInput['password']));
        $this->registryManager->getManager()->persist($newUser);
        try {
            $this->registryManager->getManager()->flush();
        } catch (Throwable) {
            return false;
        }
        return $newUser;
    }

    private function validateData(array $registerInput): bool
    {
        if (!array_key_exists('username', $registerInput)) {
            return false;
        }
        if (!array_key_exists('password', $registerInput)) {
            return false;
        }
        if (empty($registerInput['username'])) {
            return false;
        }
        if (empty($registerInput['password'])) {
            return false;
        }
        return true;
    }

    private function setUserData(User $newUser, array $registerInput): void
    {

        $newUserData = (new UserData());

        if (!empty($registerInput['firstName'])) {
            $newUserData->setFirstName($registerInput['firstName']);
        }
        if (!empty($registerInput['lastName'])) {
            $newUserData->setLastName($registerInput['lastName']);
        }
        if (!empty($registerInput['email'])) {
            $newUserData->setEmail($registerInput['email']);
        }

        $this->registryManager->getManager()->persist($newUserData);
        $this->registryManager->getManager()->flush();

        $newUser->setUserData($newUserData);
    }

    public function deleteUser(int $userId): bool
    {
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
        if (($user = $this->repository->find($id)) === null) {
            return false;
        }
        return $user;
    }

    public function attachForm(Form $form): int
    {
        $userForms = $this->registryManager->getRepository(UserForms::class)
            ->findOneBy([
                'user' => $this->security->getUser(),
            ]);
        if ($userForms === null) {
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

    public function getUser(int $id): User|int
    {
        $user = $this->repository->find($id);
        if ($user === null) {
            return 0;
        }
        return $user;
    }
}
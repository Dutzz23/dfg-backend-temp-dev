<?php

namespace App\Controller;

use App\DTO\Response\User;
use App\DTO\UserData as ImmutableUserDataSource;
use App\Entity\UserData as UserDataEntity;
use App\Lib\ObjectUpdater;
use App\Service\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $service,
        private readonly ObjectUpdater $objectUpdater
    ) {
    }

    #[Route(path: '/api/register', name: 'api_create_user', methods: ['POST'])]
    public function createUser(
        Request $request,
        AuthenticationSuccessHandler $authenticator,
        JWTTokenManagerInterface $tokenizer
    ): JsonResponse
    {
        try {
            $user = $this->service->createUser($request->getPayload()->all());
        } catch(UniqueConstraintViolationException $exception) {
            return new JsonResponse('Invalid username', Response::HTTP_EXPECTATION_FAILED);
        }
        if ($user !== false) {
            return $authenticator->handleAuthenticationSuccess($user, $tokenizer->create($user));
        }
        return new JsonResponse('User creation failed', Response::HTTP_BAD_REQUEST);
    }

    #[Route(path: '/api/user/{id}', name: 'api_get_user', methods: ['GET'])]
    public function getSingleUser(int $id, ManagerRegistry $registry): JsonResponse
    {
        $user = $this->service->getUser($id);
        if($user === 0) {
            return new JsonResponse('Unable to find user', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(User::create($id, $registry), Response::HTTP_OK);
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/api/user/{id}', name: 'api_update_user', methods: ['PATCH'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $isUpdated =$this->objectUpdater->update(
            ImmutableUserDataSource::create($request),
            UserDataEntity::class, $this->service->getUser($id)->getUserData()->getId()
        );
        if($isUpdated) {
            return new JsonResponse('User updated', Response::HTTP_OK);
        }
        return new JsonResponse(
            'Unable to update user, please check server logs for details',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    #[Route(path: '/api/user/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id) :JsonResponse
    {
        if(!$this->service->deleteUser($id)) {
            return new JsonResponse('User deletion failed', Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse('User successfully deleted', Response::HTTP_OK);
    }
}
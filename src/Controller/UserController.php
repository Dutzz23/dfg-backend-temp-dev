<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    public function __construct(
        private readonly UserService $service
    ) {
    }

    #[Route(path: '/api/user/create', name: 'api_create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        if ($this->service->createUser($request->getPayload()->all())) {
            return new JsonResponse('User created successfully', Response::HTTP_CREATED);
        }
        return new JsonResponse('User creation failed', Response::HTTP_BAD_REQUEST);
    }
}
<?php

namespace App\Controller;


use App\Service\FormService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserFormController
{

    public function __construct(
        private readonly FormService $formService,
        private readonly UserService $userService
    )
    {
    }

    #[Route(path: '/api/user/attach/{id}', name: 'api_form_attach', methods: ['POST'])]
    public function attachForm(int $id): JsonResponse
    {
        $statusCode = $this->userService->attachForm($this->formService->getById($id));
        if($statusCode !== Response::HTTP_OK) {
            return new JsonResponse('Unable to attach for to user', $statusCode);
        }
        return new JsonResponse('Form attached successfully', $statusCode);
    }
}
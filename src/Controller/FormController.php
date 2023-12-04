<?php

namespace App\Controller;

use App\DTO\Form as ImmutableFormInput;
use App\Service\FormService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController
{
    public function __construct(
        private readonly FormService $service
    )
    {
    }

    #[Route(path: '/api/form/create/public', name: 'api_form_create', methods: ['POST'])]
    public function createForm(Request $request, ManagerRegistry $registry): JsonResponse
    {
        if($this->service->create(
                ImmutableFormInput::create($request, $registry)
        ) === null) {
            return new JsonResponse('Unable to create form', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse('Form created successfully', Response::HTTP_CREATED);
    }
}
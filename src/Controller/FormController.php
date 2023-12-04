<?php

namespace App\Controller;

use App\DTO\Form as ImmutableFormInput;
use App\Service\FormService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    public function __construct(
        private readonly FormService $service,
        private readonly UserService $userService
    )
    {
    }

    #[Route(path: '/api/form/create', name: 'api_form_create', methods: ['POST'])]
    public function createPublicForm(Request $request, ManagerRegistry $registry): JsonResponse
    {
        $form = $this->service->create(
            ImmutableFormInput::create($request, $registry)
        );
        if( $form === null) {
            return new JsonResponse('Unable to create form', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->userService->attachForm($form);
        return new JsonResponse($form->getId(), Response::HTTP_CREATED);
    }

    #[Route(path: '/api/form/{id}', name: 'api_form_get', methods: ['GET'])]
    public function getPublicForm(int $id): JsonResponse
    {   $form = $this->service->getById($id);
        if($form === null) {
            return new JsonResponse('Not found', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($form, Response::HTTP_OK);
    }
}
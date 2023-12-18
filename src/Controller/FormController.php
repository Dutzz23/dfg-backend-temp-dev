<?php

namespace App\Controller;

use App\DTO\Form as ImmutableFormInput;
use App\DTO\Response\FormResponse;
use App\Service\FormService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class FormController extends AbstractController
{
    public function __construct(
        private readonly FormService $service,
        private readonly UserService $userService,
        private readonly SerializerInterface $serializer
    )
    {
    }

    #[Route(path: '/api/form/create', name: 'api_form_create', methods: ['POST'])]
    public function createPublicForm(Request $request, ManagerRegistry $registry): JsonResponse
    {
        try {
            $form = $this->service->create(
                ImmutableFormInput::create($request, $registry)
            );
        } catch (Throwable $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $this->userService->attachForm($form);
        return new JsonResponse($form->getId(), Response::HTTP_CREATED);
    }

    #[Route(path: '/api/form/view/{id}', name: 'api_form_get', methods: ['GET'])]
    public function getPublicForm(int $id): JsonResponse
    {   $form = $this->service->getById($id);
        if($form === null) {
            return new JsonResponse('Not found', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($this->serializer->serialize(FormResponse::create($form), 'json'), Response::HTTP_OK, [], true);
    }

    #[Route(path: '/api/form/user/all', name: 'api_form_get_for_user', methods: ['GET'])]
    public function getUserForms(): JsonResponse
    {
        return new JsonResponse($this->service->getFormsForUser($this->getUser(), true), 200);
    }

    #[Route(path: '/api/form/{id}/values', name: 'api_form_get_for_user', methods: ['POST'])]
    public function setFormValues(int $id, Request $request): JsonResponse
    {
        if($this->service->saveValues($id, $request->getPayload()->all(), $this->getUser())) {
           return new JsonResponse('OK', Response::HTTP_CREATED);
        }
        return new JsonResponse('Unable to save values', Response::HTTP_BAD_REQUEST);
    }

    #[Route(path: '/api/form/{id}/values', name: 'api_form_get_for_user', methods: ['GET'])]
    public function getFormValues(int $id): JsonResponse
    {
        $values = $this->service->getFormValues($id);
        if(!is_array($values)) {
            return new JsonResponse('Unable to retrieve form values', $values);
        }
        return new JsonResponse($values, Response::HTTP_OK);
    }

    #[Route(path: '/api/form/{id}/values', name: 'api_form_get_for_user', methods: ['GET'])]
    public function getFormStats(int $id): JsonResponse {
        $stats = $this->service->getFormStats($id);
        if(!is_array($stats)) {
            return new JsonResponse('Unable to retrieve form statistics', $stats);
        }
        return new JsonResponse($stats, Response::HTTP_OK);
    }

}
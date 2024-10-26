<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\DTO\RequestInput\Task\CreateTaskDTO;
use App\Service\RequestValidationService;
use App\Service\SerializerService;
use App\Service\Task\CreateTaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks', name: 'app_create_task', methods: ['POST'])]
class CreateTaskController extends AbstractController
{
    public function __construct(
        private readonly SerializerService $serializerService,
        private readonly CreateTaskService $createTaskService,
        private readonly RequestValidationService $requestValidationService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $createTaskDto = $this->serializerService->deserialize($request->getContent(), CreateTaskDTO::class, SerializerService::FORMAT_JSON);
        $this->requestValidationService->validateRequest($createTaskDto);

        $task = $this->createTaskService->createTask($createTaskDto);

        return $this->json($task, Response::HTTP_CREATED);
    }
}

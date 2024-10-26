<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use App\Service\Task\CompleteTaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tasks/{id}/complete', name: 'app_complete_task', methods: ['POST'])]
#[IsGranted('TASK_UPDATE', 'task')]
class CompleteTaskController extends AbstractController
{
    public function __construct(private readonly CompleteTaskService $completeTaskService)
    {}

    public function __invoke(Task $task, Request $request): JsonResponse
    {
        $this->completeTaskService->complete($task);

        return $this->json($task, Response::HTTP_OK);
    }
}

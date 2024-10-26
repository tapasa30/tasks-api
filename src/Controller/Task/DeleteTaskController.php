<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use App\Manager\TaskManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tasks/{id}', name: 'app_delete_task', methods: ['DELETE'])]
#[IsGranted('TASK_DELETE', 'task')]
class DeleteTaskController extends AbstractController
{
    public function __construct(private readonly TaskManager $taskManager)
    {
    }

    public function __invoke(Task $task): JsonResponse
    {
        $this->taskManager->delete($task);

        return $this->json([], Response::HTTP_OK);
    }
}

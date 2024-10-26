<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\User;
use App\Manager\TaskManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks/my-tasks', name: 'app_user_tasks', methods: ['GET'])]
class UserTasksController extends AbstractController
{

    public function __construct(private readonly TaskManager $taskManager)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $userTaskList = $this->taskManager->findAllByUser($user);

        return $this->json($userTaskList);
    }
}

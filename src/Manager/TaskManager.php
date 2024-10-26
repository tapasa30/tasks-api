<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TaskManager
{
    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }

    public function save(Task $task): void
    {
        $this->taskRepository->save($task);
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->delete($task);
    }

    public function findAllByUser(User $user): array
    {
        return $this->taskRepository->findAllByUser($user);
    }
}
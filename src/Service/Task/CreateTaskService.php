<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\DTO\RequestInput\Task\CreateTaskDTO;
use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Entity\User;
use App\Manager\TaskManager;
use App\Manager\TaskStatusManager;
use App\Manager\UserManager;
use Symfony\Bundle\SecurityBundle\Security;

class CreateTaskService
{
    public function __construct(
        private readonly TaskManager $taskManager,
        private readonly TaskStatusManager $taskStatusManager,
        private readonly Security $security,
        private readonly UserManager $userManager
    ) {
    }

    public function createTask(CreateTaskDTO $createTaskDTO): Task
    {
        $task = new Task();

        $toDoTaskStatus = $this->taskStatusManager->getByCode(TaskStatus::TO_DO);

        $task->setTitle($createTaskDTO->getTitle());
        $task->setDescription($createTaskDTO->getDescription());
        $task->setStatus($toDoTaskStatus);

        $this->setOwner($createTaskDTO, $task);

        $this->taskManager->save($task);

        return $task;
    }

    public function setOwner(CreateTaskDTO $createTaskDTO, Task $task): void
    {
        if ($this->security->isGranted('ROLE_SUPER_ADMIN') && $createTaskDTO->getUserId() !== null) {
            $taskOwner = $this->userManager->getById($createTaskDTO->getUserId());

            $task->setOwner($taskOwner);

            return;
        }

        /** @var User $loggedUser */
        $loggedUser = $this->security->getUser();

        $task->setOwner($loggedUser);
    }
}
<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Manager\TaskManager;
use App\Manager\TaskStatusManager;

class CompleteTaskService
{
    public function __construct(
        private readonly TaskManager $taskManager,
        private readonly TaskStatusManager $taskStatusManager
    ) {
    }

    public function complete(Task $task): void
    {
        $completeTaskStatus = $this->taskStatusManager->getByCode(TaskStatus::COMPLETED);

        $task->setStatus($completeTaskStatus);

        $this->taskManager->save($task);
    }
}
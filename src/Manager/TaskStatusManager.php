<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\TaskStatus;
use App\Repository\TaskStatusRepository;

class TaskStatusManager
{
    public function __construct(private readonly TaskStatusRepository $userRepository)
    {
    }

    public function getByCode(string $code): TaskStatus
    {
        return $this->userRepository->getByCode($code);
    }
}
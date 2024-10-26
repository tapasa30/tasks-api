<?php

declare(strict_types=1);

namespace App\DTO\RequestInput\Task;

use App\DTO\RequestInput\RequestDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTaskDTO implements RequestDTOInterface
{
    #[Assert\NotNull]
    #[Assert\Type('string')]
    private string $title;

    #[Assert\Type('string')]
    private ?string $description = null;

    #[Assert\Type('int')]
    private ?int $userId = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}

<?php

namespace App\Entity;

use App\Repository\TaskStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskStatusRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_STATUS_CODE', fields: ['code'])]
class TaskStatus
{
    public const string TO_DO = 'to_do';
    public const string COMPLETED = 'completed';

    public const array STATUS_CODES = [
        self::TO_DO,
        self::COMPLETED
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}

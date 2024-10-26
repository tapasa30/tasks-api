<?php

declare(strict_types=1);

namespace App\DTO\User;

class IdentifiedUserDTO
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}

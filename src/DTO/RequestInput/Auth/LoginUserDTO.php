<?php

declare(strict_types=1);

namespace App\DTO\RequestInput\Auth;

use App\DTO\RequestInput\RequestDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginUserDTO implements RequestDTOInterface
{
    #[Assert\Email]
    #[Assert\NotNull]
    private string $email;

    #[Assert\NotNull]
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

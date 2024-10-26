<?php

declare(strict_types=1);

namespace App\DTO\RequestInput\Auth;

use App\DTO\RequestInput\RequestDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class RegisterUserDTO implements RequestDTOInterface
{
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\NoSuspiciousCharacters]
    private string $username;

    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Email]
    private string $email;

    #[Assert\NotNull]
    #[Assert\PasswordStrength(
        minScore: PasswordStrength::STRENGTH_WEAK
    )]
    #[Assert\Length(min: 8)]
    private string $password;

    #[Assert\NotNull]
    #[Assert\EqualTo(propertyPath: 'password')]
    private string $repeatedPassword;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRepeatedPassword(): string
    {
        return $this->repeatedPassword;
    }
}

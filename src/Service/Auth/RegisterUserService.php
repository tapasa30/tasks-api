<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\DTO\RequestInput\Auth\RegisterUserDTO;
use App\Entity\User;
use App\Exception\EmailAlreadyExistsException;
use App\Manager\UserManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserManager $userManager,
    ) {
    }

    public function register(RegisterUserDTO $registerUserDto): User
    {
        $user = $this->userManager->findOneByEmail($registerUserDto->getEmail());

        if ($user !== null) {
            throw new EmailAlreadyExistsException(
                'User with email "'.$registerUserDto->getEmail().'" already exists in Database'
            );
        }

        $user = new User();
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $registerUserDto->getPassword());

        $user->setUsername($registerUserDto->getUsername());
        $user->setEmail($registerUserDto->getEmail());
        $user->setPassword($hashedPassword);

        $this->userManager->save($user);

        return $user;
    }
}
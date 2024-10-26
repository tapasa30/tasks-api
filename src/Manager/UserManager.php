<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;

class UserManager
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->userRepository->findOneByEmail($email);
    }

    public function getById(int $id): User
    {
        return $this->userRepository->getById($id);
    }

    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }
}
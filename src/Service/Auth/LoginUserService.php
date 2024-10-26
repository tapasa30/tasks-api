<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\DTO\RequestInput\Auth\LoginUserDTO;
use App\Exception\InvalidLoginException;
use App\Manager\UserManager;
use App\Service\JwtService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginUserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly JwtService $jwtService,
        private readonly UserManager $userManager,
    ) {
    }

    public function login(LoginUserDTO $loginUserDTO): array
    {
        $user = $this->userManager->findOneByEmail($loginUserDTO->getEmail());

        if ($user === null) {
            throw new InvalidLoginException();
        }

        $isPasswordValid = $this->userPasswordHasher->isPasswordValid($user, $loginUserDTO->getPassword());

        if (!$isPasswordValid) {
            throw new InvalidLoginException();
        }

        $jwtToken = $this->jwtService->generateAccessToken($user);

        return [
            'message' => 'success!',
            'token' => $jwtToken,
            'token_type' => JwtService::TOKEN_PREFIX
        ];
    }
}
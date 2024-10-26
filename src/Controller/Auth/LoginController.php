<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\DTO\RequestInput\Auth\LoginUserDTO;
use App\Exception\InvalidLoginException;
use App\Service\Auth\LoginUserService;
use App\Service\RequestValidationService;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/login', name: 'app_login', methods: ['POST'])]
class LoginController extends AbstractController
{
    public function __construct(
        private readonly RequestValidationService $requestValidationService,
        private readonly SerializerService $serializerService,
        private readonly LoginUserService $loginUserService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $userLoginDataDTO = $this->serializerService
            ->deserialize($request->getContent(), LoginUserDTO::class, SerializerService::FORMAT_JSON);

        $this->requestValidationService->validateRequest($userLoginDataDTO);

        try {
            $loginAuthData = $this->loginUserService->login($userLoginDataDTO);
        } catch (InvalidLoginException) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($loginAuthData);
    }
}

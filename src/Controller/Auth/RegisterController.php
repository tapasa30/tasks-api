<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\DTO\RequestInput\Auth\RegisterUserDTO;
use App\Exception\EmailAlreadyExistsException;
use App\Service\Auth\RegisterUserService;
use App\Service\RequestValidationService;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/register', name: 'app_register', methods: ['POST'])]
class RegisterController extends AbstractController
{
    public function __construct(
        private readonly RequestValidationService $requestValidationService,
        private readonly SerializerService $serializerService,
        private readonly RegisterUserService $registerUserService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $registerUserDto = $this->serializerService->deserialize($request->getContent(), RegisterUserDTO::class, SerializerService::FORMAT_JSON);
        $this->requestValidationService->validateRequest($registerUserDto);

        try {
            $user = $this->registerUserService->register($registerUserDto);
        } catch (EmailAlreadyExistsException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($user, Response::HTTP_CREATED);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/{id}', name: 'app_get_user', methods: ['GET'])]
class GetUserController extends AbstractController
{
    public function __invoke(User $user): JsonResponse
    {
        return $this->json($user);
    }
}

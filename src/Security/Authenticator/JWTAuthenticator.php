<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authorizationToken = $request->headers->get('Authorization');

        if ($authorizationToken === null) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        try {
            $identifiedUserDto = $this->jwtService->getIdentifiedUser($authorizationToken);
        } catch (\Throwable $exception) {
            throw new CustomUserMessageAuthenticationException($exception->getMessage());
        }

        return new SelfValidatingPassport(new UserBadge($identifiedUserDto->getEmail()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}

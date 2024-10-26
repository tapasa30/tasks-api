<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\User\IdentifiedUserDTO;
use App\Entity\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class JwtService
{
    public const string TOKEN_PREFIX = 'Bearer ';
    public const string ALGORITHM_HS_256 = 'HS256';

    public function __construct(
        private readonly SerializerService $serializerService,
        private readonly string $secretKey
    ) {
    }

    public function decodeAuthenticationToken(string $authorizationToken): array
    {
        if (!str_starts_with($authorizationToken, self::TOKEN_PREFIX)) {
            throw new CustomUserMessageAuthenticationException('Invalid token provided');
        }

        $jwtToken = \str_replace(self::TOKEN_PREFIX, '', $authorizationToken);

        try {
            $decodedJwtToken = JWT::decode($jwtToken, new Key($this->secretKey, self::ALGORITHM_HS_256));
        } catch (ExpiredException $exception) {
            throw new UnauthorizedHttpException('JWT', $exception->getMessage());
        }

        return (array)$decodedJwtToken;
    }

    public function getIdentifiedUser(string $authorizationToken): IdentifiedUserDTO
    {
        $decodedToken = $this->decodeAuthenticationToken($authorizationToken);

        if (!\array_key_exists('data', $decodedToken)) {
            throw new \UnexpectedValueException('Missing token data');
        }

        return $this->serializerService->deserialize(
            json_encode($decodedToken['data']),
            IdentifiedUserDTO::class,
            SerializerService::FORMAT_JSON
        );
    }

    public function generateAccessToken(User $user): string
    {
        $currentTimestamp = new \DateTimeImmutable();

        $issuedAt = $currentTimestamp->getTimestamp();
        $expire = $currentTimestamp->modify('+1 week')->getTimestamp();

        $identifiedUser = $this->serializerService->serialize(
            new IdentifiedUserDTO($user->getEmail()),
            SerializerService::FORMAT_JSON
        );

        $data = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => json_decode($identifiedUser, true),
        ];

        return JWT::encode($data, $this->secretKey, self::ALGORITHM_HS_256);
    }
}

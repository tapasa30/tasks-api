<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\RequestInput\RequestDTOInterface;
use App\Exception\RequestValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidationService
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validateRequest(RequestDTOInterface $requestDTO): void
    {
        $validationErrors = $this->validator->validate($requestDTO);

        if ($validationErrors->count() === 0) {
            return;
        }

        throw new RequestValidationException($validationErrors);
    }
}

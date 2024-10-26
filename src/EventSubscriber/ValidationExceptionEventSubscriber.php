<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\RequestValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ValidationExceptionEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
    
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if (!($exception instanceof RequestValidationException)) {
            return;
        }

        $errors = [];
        $validationErrors = $exception->getConstraintViolationList();

        foreach ($validationErrors as $validation) {
            $errors[] = [
                'property' => $validation->getPropertyPath(),
                'value' => $validation->getInvalidValue(),
                'message' => $validation->getMessage(),
            ];
        }

        $jsonResponse = new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);

        $event->setResponse($jsonResponse);
    }
}
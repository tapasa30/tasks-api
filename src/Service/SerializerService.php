<?php

declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class SerializerService
{
    public const string FORMAT_JSON = 'json';

    private readonly Serializer $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function serialize($data, string $format): string
    {
        return $this->serializer->serialize($data, $format);
    }

    public function deserialize(string $data, string $className, string $format)
    {
        return $this->serializer->deserialize($data, $className, $format);
    }
}

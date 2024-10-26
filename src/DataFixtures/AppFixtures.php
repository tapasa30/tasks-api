<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (TaskStatus::STATUS_CODES as $statusCode) {
            $taskStatus = new TaskStatus();

            $taskStatus->setCode($statusCode);

            $manager->persist($taskStatus);
        }

        $manager->flush();
    }
}

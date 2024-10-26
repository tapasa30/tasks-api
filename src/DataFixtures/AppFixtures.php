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
        $taskStatusRepository = $manager->getRepository(TaskStatus::class);

        foreach (TaskStatus::STATUS_CODES as $statusCode) {
            $taskStatus = new TaskStatus();

            $existingTaskStatus = $taskStatusRepository->findOneBy(['code' => $statusCode]);

            if ($existingTaskStatus !== null) {
                continue;
            }

            $taskStatus->setCode($statusCode);

            $manager->persist($taskStatus);
        }

        $manager->flush();
    }
}

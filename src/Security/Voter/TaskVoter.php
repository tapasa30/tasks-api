<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const string READ = 'TASK_READ';
    const string UPDATE = 'TASK_UPDATE';
    const string DELETE = 'TASK_DELETE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE])) {
            return false;
        }

        return $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $loggedUser = $token->getUser();

        if (!($loggedUser instanceof User)) {
            return false;
        }

        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        return match($attribute) {
            self::READ => $this->canRead($subject, $loggedUser),
            self::UPDATE => $this->canUpdate($subject, $loggedUser),
            self::DELETE => $this->canDelete($subject, $loggedUser),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canRead(Task $task, User $loggedUser): bool
    {
        return $this->canUpdate($task, $loggedUser);
    }

    private function canUpdate(Task $task, User $loggedUser): bool
    {
        return $task->getOwner() === $loggedUser;
    }

    private function canDelete(Task $task, User $loggedUser): bool
    {
        return $this->canUpdate($task, $loggedUser);
    }
}

<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const string READ = 'USER_READ';
    const string UPDATE = 'USER_UPDATE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::READ, self::UPDATE])) {
            return false;
        }

        return $subject instanceof User;
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
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canRead(User $user, User $loggedUser): bool
    {
        return $this->canUpdate($user, $loggedUser);
    }

    private function canUpdate(User $user, User $loggedUser): bool
    {
        return $user->getId() === $loggedUser->getId();
    }
}

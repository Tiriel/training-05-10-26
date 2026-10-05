<?php

namespace App\Security\Voter;

use App\Entity\Conference;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EditionVoter extends Voter
{
    public const CONFERENCE = 'edit.conference';

    public function __construct(protected readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CONFERENCE === $attribute
            && $subject instanceof Conference;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted('ROLE_WEBSITE')) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }


        /** @var Conference $subject */
        foreach ($subject->getOrganizations() as $organization) {
            if ($user->getOrganizations()->contains($organization)) {
                return true;
            }
        }

        return $user === $subject->getCreatedBy();
    }
}

<?php

namespace App\Security\Voter;

use App\Entity\Conference;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EditionVoter extends Voter
{
    public const CONFERENCE = 'edit.conference';

    public function __construct(protected readonly RoleHierarchyInterface $hierarchy)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CONFERENCE === $attribute
            && $subject instanceof Conference;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (\in_array('ROLE_WEBSITE', $this->hierarchy->getReachableRoleNames($user->getRoles()), true)) {
            return true;
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

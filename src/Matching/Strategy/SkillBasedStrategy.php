<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use App\Matching\Strategy\MatchingStrategyInterface;
use App\Repository\ConferenceRepository;

class SkillBasedStrategy implements MatchingStrategyInterface
{
    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function match(User $user): iterable
    {
        return $this->repository->findForSkills($user);
    }

    public static function getName(): string
    {
        return 'skill';
    }
}

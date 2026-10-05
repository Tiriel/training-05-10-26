<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use App\Repository\ConferenceRepository;

class TagBasedStrategy implements MatchingStrategyInterface
{
    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function match(User $user): iterable
    {
        return $this->repository->findForTags($user);
    }

    public static function getName(): string
    {
        return 'tag';
    }
}

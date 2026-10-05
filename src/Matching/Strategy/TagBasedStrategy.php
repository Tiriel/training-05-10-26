<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use App\Repository\ConferenceRepository;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
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

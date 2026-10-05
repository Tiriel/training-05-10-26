<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['app.matching_strategy'], lazy: true)]
interface MatchingStrategyInterface
{
    public function match(User $user): iterable;

    public static function getName(): string;
}

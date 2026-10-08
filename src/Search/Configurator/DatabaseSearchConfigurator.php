<?php

namespace App\Search\Configurator;

use App\Entity\Organization;
use App\Entity\User;
use App\Search\DatabaseConferenceSearch;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DatabaseSearchConfigurator
{
    public function __construct(
        private readonly Security $security,
        #[Autowire(env: 'int:SEARCH_MAX_RESULTS')]
        private readonly int $maxResults,
    ) {}

    public function configure(DatabaseConferenceSearch $search)
    {
        $orgIds = [];

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $orgIds = $user
                ->getOrganizations()
                ->map(fn (Organization $org) => $org->getId())
                ;
        }

        $search
            ->setOrgIds($orgIds)
            ->setMaxResults($this->maxResults);
    }
}

<?php

namespace App\Repository;

use App\Entity\Conference;
use App\Entity\Skill;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    /**
     * @return Conference[]
     */
    public function findConferencesBetweenDates(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        if (null === $start && null === $end) {
            throw new \InvalidArgumentException('At least one date is required to operate this method.');
        }

        $qb = $this->createQueryBuilder('c');

        if ($start instanceof \DateTimeImmutable) {
            $qb->andWhere('c.startAt >= :start')
                ->setParameter('start', $start);
        }

        if ($end instanceof \DateTimeImmutable) {
            $qb->andWhere('c.endAt <= :end')
                ->setParameter('end', $end);
        }

        return $qb->getQuery()->getResult();
    }

    public function findLikeName(string $name): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->where($qb->expr()->like('c.name', ':name'))
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function findForTags(User $user): iterable
    {
        $qb = $this->createQueryBuilder('c');
        $tagIds = $user
            ->getVolunteerProfile()
            ->getInterests()
            ->map(fn(Tag $tag) => $tag->getId());

        return $qb
            ->innerJoin('c.tags', 't')
            ->where($qb->expr()->in('t.id', ':tagIds'))
            ->setParameter('tagIds', $tagIds)
            ->groupBy('c.id')
            ->orderBy($qb->expr()->count('t.id'), 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findForSkills(User $user): iterable
    {
        $qb = $this->createQueryBuilder('c');
        $skillIds = $user
            ->getVolunteerProfile()
            ->getSkills()
            ->map(fn(Skill $skill) => $skill->getId());

        return $qb
            ->innerJoin('c.neededSkills', 's')
            ->where($qb->expr()->in('s.id', ':skillIds'))
            ->setParameter('skillIds', $skillIds)
            ->groupBy('c.id')
            ->orderBy($qb->expr()->count('s.id'), 'DESC')
            ->getQuery()
            ->getResult();
    }
}

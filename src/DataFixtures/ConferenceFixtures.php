<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConferenceFixtures extends Fixture
{
    public const SF_LIVE = 'sf_live_';

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $year = '20' . str_pad($i, 2, 0, STR_PAD_LEFT);
            $conference = (new Conference())
                ->setName('SymfonyLive '.$year)
                ->setDescription('Share your best practices, experience and knowledge with Symfony.')
                ->setAccessible(true)
                ->setStartAt(new \DateTimeImmutable('28-03-'.$year))
                ->setEndAt(new \DateTimeImmutable('29-03-'.$year))
            ;

            foreach ((array) array_rand(TagFixtures::TAGS, rand(1, 3)) as $key) {
                $name = TagFixtures::TAGS[$key];
                $conference->addTag($this->getReference(TagFixtures::TAG_NAME.$name, Tag::class));
            }

            $manager->persist($conference);
            $manager->flush();
            $this->addReference(self::SF_LIVE.$i, $conference);
        }
    }
}

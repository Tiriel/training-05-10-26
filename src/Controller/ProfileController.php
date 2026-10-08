<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\VolunteerProfile;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        #[CurrentUser] User $user,
        EntityManagerInterface $entityManager,
        #[Target('volunteer.matches.cache')]
        CacheItemPoolInterface $cache,
    ): Response
    {
        if (!$user->getVolunteerProfile()) {
            $user->setVolunteerProfile((new VolunteerProfile())->setForUser($user));
            $entityManager->flush();
        }

        $key = sprintf("%d-%s", $user->getId(), $user->getVolunteerProfile()->getUpdatedAt()->format('Y-m-d'));
        // Read-only: only MatchVolunteerMessageHandler writes matches
        $item = $cache->getItem($key);
        $matches = $item->isHit() ? $item->get() : [];

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'matches' => $matches,
        ]);
    }
}

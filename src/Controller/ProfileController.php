<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\VolunteerProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        #[CurrentUser] User $user,
        EntityManagerInterface $entityManager,
        #[Target('volunteer.matches.cache')]
        TagAwareCacheInterface $cache,
    ): Response
    {
        if (!$user->getVolunteerProfile()) {
            $user->setVolunteerProfile((new VolunteerProfile())->setForUser($user));
            $entityManager->flush();
        }

        $key = sprintf("%d-%s", $user->getId(), $user->getVolunteerProfile()->getUpdatedAt()->format('Y-m-d'));
        $matches = $cache->get($key, static function (ItemInterface $item, bool &$save): array {
            // Only MatchVolunteerMessageHandler may store matches: caching this empty fallback would hide its result
            $save = false;

            return [];
        });

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'matches' => $matches,
        ]);
    }
}

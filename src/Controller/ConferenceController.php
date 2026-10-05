<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\User;
use App\Form\ConferenceType;
use App\Matching\Strategy\TagBasedStrategy;
use App\Message\MatchVolunteerMessage;
use App\Search\ConferenceSearchInterface;
use App\Search\DatabaseConferenceSearch;
use App\Security\Voter\EditionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ConferenceController extends AbstractController
{
    // Possible solution to SF_ADVANCED exercise 14
    //#[IsGranted(new Expression('is_granted("ROLE_ORGANIZER") or is_granted("ROLE_WEBSITE")'))]
    #[Route('/conference/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    #[Route('/conference/{id<\d+>}/edit', name: 'app_conference_edit', methods: ['GET', 'POST'])]
    public function newConference(?Conference $conference, Request $request, EntityManagerInterface $manager): Response
    {
        // Possible solution to SF_ADVANCED exercise 14
        //if (!$this->isGranted('ROLE_ORGANIZER') && !$this->isGranted('ROLE_WEBSITE')) {
        //    throw $this->createAccessDeniedException();
        //}
        if ($conference instanceof Conference) {
            $this->denyAccessUnlessGranted(EditionVoter::CONFERENCE, $conference);
        }

        $conference ??= new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$conference->getId()) {
                $conference->setCreatedBy($this->getUser());
            }

            $manager->persist($conference);
            $manager->flush();

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
            'conference' => $conference,
        ]);
    }

    #[Route('/conference', name: 'app_conference_list', methods: ['GET'])]
    public function list(Request $request, DatabaseConferenceSearch $search): Response
    {
        return $this->render('conference/list.html.twig', [
            'conferences' => $search->search($request->query->get('name')),
        ]);
    }

    #[Route('/conference/search', name: 'app_conference_search', methods: ['GET'])]
    #[Template('conference/list.html.twig')]
    public function search(Request $request, ConferenceSearchInterface $search): array
    {
        return ['conferences' => $search->search($request->query->get('name'))];
    }

    #[Route('/conference/{id}', name: 'app_conference_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    #[Route('/conferences/match/{strategy}', name: 'app_conference_match', requirements: ['strategy' => 'tag|skill|location'])]
    public function match(string $strategy, #[CurrentUser] User $user, TagBasedStrategy $tagStrategy): Response
    {
        return $this->render('conference/list.html.twig', [
            'conferences' => $tagStrategy->match($user),
        ]);
    }
}

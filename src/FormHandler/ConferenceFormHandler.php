<?php

namespace App\FormHandler;

use App\Entity\Conference;
use App\Form\ConferenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerHelper;
use Symfony\Component\DependencyInjection\Attribute\AutowireMethodOf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConferenceFormHandler
{
    public function __construct(
        // $createForm = (new ControllerHelper())->createForm(...)
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly \Closure $createForm,
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly \Closure $getUser,
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly \Closure $redirectToRoute,
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly \Closure $render,
        private readonly EntityManagerInterface $manager,
    ) {}

    public function handle(Request $request, ?Conference $conference): Response
    {
        $form = ($this->createForm)(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$conference->getId()) {
                $conference->setCreatedBy(($this->getUser)());
            }

            $this->manager->persist($conference);
            $this->manager->flush();

            return ($this->redirectToRoute)('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
            'conference' => $conference,
        ]);
    }

    private function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        return ($this->render)($view, $parameters, $response);
    }
}

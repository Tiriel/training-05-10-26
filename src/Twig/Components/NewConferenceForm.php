<?php

namespace App\Twig\Components;

use App\Entity\Conference;
use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class NewConferenceForm
{
    use FormWithOrganizationsTrait;
    use FormWithTagsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 10)]
    public string $name = '';

    #[LiveProp(writable: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 30)]
    public string $description = '';

    #[LiveProp(writable: true)]
    public bool $isAccessible = false;

    #[LiveProp(writable: true)]
    #[Assert\Length(min: 20)]
    public ?string $prerequisites = null;

    #[LiveProp(writable: true, format: 'Y-m-d')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('today')]
    public ?\DateTime $startAt = null;

    #[LiveProp(writable: true, format: 'Y-m-d')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startAt')]
    public ?\DateTime $endAt = null;

    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly UrlGeneratorInterface  $urlGenerator,
    ) {
    }

    #[LiveAction]
    public function saveConference(EntityManagerInterface $manager): Response
    {
        $this->validate();

        $conference = (new Conference())
            ->setName($this->name)
            ->setDescription($this->description)
            ->setAccessible($this->isAccessible)
            ->setStartAt(\DateTimeImmutable::createFromMutable($this->startAt))
            ->setEndAt(\DateTimeImmutable::createFromMutable($this->endAt))
        ;

        if (null !== $this->prerequisites) {
            $conference->setPrerequisites($this->prerequisites);
        }

        foreach ($this->organizations as $organization) {
            $conference->addOrganization($organization);
        }

        foreach ($this->tags as $tag) {
            $conference->addTag($tag);
        }

        $manager->persist($conference);
        $manager->flush();

        return new RedirectResponse($this->urlGenerator->generate('app_conference_show', ['id' => $conference->getId()]));
    }
}

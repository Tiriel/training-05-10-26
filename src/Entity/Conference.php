<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[Groups(['Volunteering'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 10)]
    #[Assert\NotNull()]
    #[Groups(['Volunteering'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 30)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\NotNull()]
    #[ORM\Column]
    private ?bool $accessible = null;

    #[Assert\Length(min: 20)]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prerequisites = null;

    #[Assert\GreaterThan('today')]
    #[Assert\NotNull()]
    #[Groups(['Volunteering'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[Assert\GreaterThan(propertyPath: 'startAt')]
    #[Assert\NotNull()]
    #[Groups(['Volunteering'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    /**
     * @var Collection<int, Volunteering>
     */
    #[ORM\OneToMany(targetEntity: Volunteering::class, mappedBy: 'conference', orphanRemoval: true)]
    private Collection $volunteerings;

    /**
     * @var Collection<int, Organization>
     */
    #[Assert\NotNull()]
    #[Assert\Valid()]
    #[Groups(['Volunteering'])]
    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'conferences')]
    private Collection $organizations;

    #[ORM\ManyToOne(inversedBy: 'conferences')]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class)]
    private Collection $neededSkills;

    public function __construct()
    {
        $this->volunteerings = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->neededSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isAccessible(): ?bool
    {
        return $this->accessible;
    }

    public function setAccessible(bool $accessible): static
    {
        $this->accessible = $accessible;

        return $this;
    }

    public function getPrerequisites(): ?string
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(?string $prerequisites): static
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection<int, Volunteering>
     */
    public function getVolunteerings(): Collection
    {
        return $this->volunteerings;
    }

    public function addVolunteering(Volunteering $volunteering): static
    {
        if (!$this->volunteerings->contains($volunteering)) {
            $this->volunteerings->add($volunteering);
            $volunteering->setConference($this);
        }

        return $this;
    }

    public function removeVolunteering(Volunteering $volunteering): static
    {
        if ($this->volunteerings->removeElement($volunteering)) {
            // set the owning side to null (unless already changed)
            if ($volunteering->getConference() === $this) {
                $volunteering->setConference(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        $this->organizations->removeElement($organization);

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getNeededSkills(): Collection
    {
        return $this->neededSkills;
    }

    public function addNeededSkill(Skill $neededSkill): static
    {
        if (!$this->neededSkills->contains($neededSkill)) {
            $this->neededSkills->add($neededSkill);
        }

        return $this;
    }

    public function removeNeededSkill(Skill $neededSkill): static
    {
        $this->neededSkills->removeElement($neededSkill);

        return $this;
    }
}

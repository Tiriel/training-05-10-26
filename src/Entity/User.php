<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups('Volunteering')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('Volunteering')]
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Volunteering>
     */
    #[ORM\OneToMany(targetEntity: Volunteering::class, mappedBy: 'forUser', orphanRemoval: true)]
    private Collection $volunteerings;

    /**
     * @var Collection<int, Organization>
     */
    #[Groups('Volunteering')]
    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'users')]
    private Collection $organizations;

    /**
     * @var Collection<int, Conference>
     */
    #[ORM\OneToMany(targetEntity: Conference::class, mappedBy: 'createdBy')]
    private Collection $conferences;

    #[ORM\Column(length: 255)]
    private ?string $apiKey = null;

    #[ORM\OneToOne(mappedBy: 'forUser', cascade: ['persist', 'remove'])]
    private ?VolunteerProfile $volunteerProfile = null;

    public function __construct()
    {
        $this->volunteerings = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->conferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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
            $volunteering->setForUser($this);
        }

        return $this;
    }

    public function removeVolunteering(Volunteering $volunteering): static
    {
        if ($this->volunteerings->removeElement($volunteering)) {
            // set the owning side to null (unless already changed)
            if ($volunteering->getForUser() === $this) {
                $volunteering->setForUser(null);
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

    /**
     * @return Collection<int, Conference>
     */
    public function getConferences(): Collection
    {
        return $this->conferences;
    }

    public function addConference(Conference $conference): static
    {
        if (!$this->conferences->contains($conference)) {
            $this->conferences->add($conference);
            $conference->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConference(Conference $conference): static
    {
        if ($this->conferences->removeElement($conference)) {
            // set the owning side to null (unless already changed)
            if ($conference->getCreatedBy() === $this) {
                $conference->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getVolunteerProfile(): ?VolunteerProfile
    {
        return $this->volunteerProfile;
    }

    public function setVolunteerProfile(?VolunteerProfile $volunteerProfile): User
    {
        $this->volunteerProfile = $volunteerProfile;
        $volunteerProfile->setForUser($this);

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(): static
    {
        $this->apiKey = password_hash(base64_encode(random_bytes(48)), PASSWORD_BCRYPT);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'This email is already in use.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 180)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 180)]
    private ?string $department = null;

    #[ORM\Column(type: 'datetime', nullable: false)] 
    private ?\DateTimeInterface $createdAt = null;

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
     * @var Collection<int, Feedback>
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'user')]
    private Collection $feedback;

    /**
     * @var Collection<int, Goals>
     */
    #[ORM\OneToMany(targetEntity: Goals::class, mappedBy: 'user')]
    private Collection $goal;

    /**
     * @var Collection<int, Goals>
     */
    #[ORM\OneToMany(targetEntity: Goals::class, mappedBy: 'reporter')]
    private Collection $goal_reporter;

    /**
     * @var Collection<int, Feedback>
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'reporter')]
    private Collection $feedback_reporter;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->feedback = new ArrayCollection();
        $this->goal = new ArrayCollection();
        $this->goal_reporter = new ArrayCollection();
        $this->feedback_reporter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
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
    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setUser($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getUser() === $this) {
                $feedback->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Goals>
     */
    public function getGoal(): Collection
    {
        return $this->goal;
    }

    public function addGoal(Goals $goal): self
    {
        if (!$this->goal->contains($goal)) {
            $this->goal->add($goal);
            $goal->setUser($this);
        }

        return $this;
    }

    public function removeGoal(Goals $goal): self
    {
        if ($this->goal->removeElement($goal)) {
            // set the owning side to null (unless already changed)
            if ($goal->getUser() === $this) {
                $goal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Goals>
     */
    public function getGoalReporter(): Collection
    {
        return $this->goal_reporter;
    }

    public function addGoalReporter(Goals $goalReporter): self
    {
        if (!$this->goal_reporter->contains($goalReporter)) {
            $this->goal_reporter->add($goalReporter);
            $goalReporter->setReporter($this);
        }

        return $this;
    }

    public function removeGoalReporter(Goals $goalReporter): self
    {
        if ($this->goal_reporter->removeElement($goalReporter)) {
            // set the owning side to null (unless already changed)
            if ($goalReporter->getReporter() === $this) {
                $goalReporter->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedbackReporter(): Collection
    {
        return $this->feedback_reporter;
    }

    public function addFeedbackReporter(Feedback $feedbackReporter): self
    {
        if (!$this->feedback_reporter->contains($feedbackReporter)) {
            $this->feedback_reporter->add($feedbackReporter);
            $feedbackReporter->setReporter($this);
        }

        return $this;
    }

    public function removeFeedbackReporter(Feedback $feedbackReporter): self
    {
        if ($this->feedback_reporter->removeElement($feedbackReporter)) {
            // set the owning side to null (unless already changed)
            if ($feedbackReporter->getReporter() === $this) {
                $feedbackReporter->setReporter(null);
            }
        }

        return $this;
    }
}

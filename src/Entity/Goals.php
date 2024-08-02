<?php

namespace App\Entity;

use App\Repository\GoalsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoalsRepository::class)]
class Goals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime', nullable: true)] 
    private ?\DateTimeInterface $completed_date = null;

    #[ORM\ManyToOne(inversedBy: 'goals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GoalsCategory $category = null;

    #[ORM\ManyToOne(inversedBy: 'goal')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'goal_reporter')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reporter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getCompletedDate(): ?\DateTimeInterface
    {
        return $this->completed_date;
    }

    public function setCompletedDate(?\DateTimeInterface $completed_date): self
    {
        $this->completed_date = $completed_date;

        return $this;
    }

    public function getCategory(): ?GoalsCategory
    {
        return $this->category;
    }

    public function setCategory(?GoalsCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReporter(): ?user
    {
        return $this->reporter;
    }

    public function setReporter(?user $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }
}

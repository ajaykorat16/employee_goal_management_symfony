<?php

namespace App\Entity;

use App\Repository\GoalsCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoalsCategoryRepository::class)]
class GoalsCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Goals>
     */
    #[ORM\OneToMany(targetEntity: Goals::class, mappedBy: 'category')]
    private Collection $goals;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Goals>
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goals $goal): self
    {
        if (!$this->goals->contains($goal)) {
            $this->goals->add($goal);
            $goal->setCategory($this);
        }

        return $this;
    }

    public function removeGoal(Goals $goal): self
    {
        if ($this->goals->removeElement($goal)) {
            // set the owning side to null (unless already changed)
            if ($goal->getCategory() === $this) {
                $goal->setCategory(null);
            }
        }

        return $this;
    }
}

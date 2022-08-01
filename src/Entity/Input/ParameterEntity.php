<?php

namespace App\Entity\Input;

use App\Entity\Analysis\Anomaly\RuleEntity;
use App\Entity\Analysis\Pattern\FilterEntity;
use App\Repository\Input\ParameterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParameterRepository::class)]
#[ORM\Table(name: 'input_parameters')]
class ParameterEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parameters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RequestEntity $request = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $path = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\Column]
    private ?bool $threat = null;

    #[ORM\Column]
    private ?int $totalAnomalyRules = null;

    #[ORM\Column]
    private ?bool $criticalImpact = null;

    #[ORM\ManyToMany(targetEntity: RuleEntity::class)]
    private Collection $brokenAnomalyRules;

    #[ORM\ManyToMany(targetEntity: FilterEntity::class)]
    private Collection $matchingPatternFilters;

    public function __construct()
    {
        $this->brokenAnomalyRules = new ArrayCollection();
        $this->matchingPatternFilters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?RequestEntity
    {
        return $this->request;
    }

    public function setRequest(?RequestEntity $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function isThreat(): ?bool
    {
        return $this->threat;
    }

    public function setThreat(bool $threat): self
    {
        $this->threat = $threat;

        return $this;
    }

    public function getTotalAnomalyRules(): ?int
    {
        return $this->totalAnomalyRules;
    }

    public function setTotalAnomalyRules(int $totalAnomalyRules): self
    {
        $this->totalAnomalyRules = $totalAnomalyRules;

        return $this;
    }

    public function isCriticalImpact(): ?bool
    {
        return $this->criticalImpact;
    }

    public function setCriticalImpact(bool $criticalImpact): self
    {
        $this->criticalImpact = $criticalImpact;

        return $this;
    }

    public function getBrokenAnomalyRules(): Collection
    {
        return $this->brokenAnomalyRules;
    }

    public function addBrokenAnomalyRule(RuleEntity $brokenAnomalyRule): self
    {
        if (!$this->brokenAnomalyRules->contains($brokenAnomalyRule)) {
            $this->brokenAnomalyRules->add($brokenAnomalyRule);
        }

        return $this;
    }

    public function removeBrokenAnomalyRule(RuleEntity $brokenAnomalyRule): self
    {
        $this->brokenAnomalyRules->removeElement($brokenAnomalyRule);

        return $this;
    }

    public function getMatchingPatternFilters(): Collection
    {
        return $this->matchingPatternFilters;
    }

    public function addMatchingPatternFilter(FilterEntity $matchingPatternFilter): self
    {
        if (!$this->matchingPatternFilters->contains($matchingPatternFilter)) {
            $this->matchingPatternFilters->add($matchingPatternFilter);
        }

        return $this;
    }

    public function removeMatchingPatternFilter(FilterEntity $matchingPatternFilter): self
    {
        $this->matchingPatternFilters->removeElement($matchingPatternFilter);

        return $this;
    }
}

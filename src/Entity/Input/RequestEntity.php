<?php

namespace App\Entity\Input;

use App\Entity\Analysis\Integrity\RuleEntity;
use App\Entity\Domain\ProfileEntity;
use App\Repository\Input\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\Table(name: 'input_requests')]
class RequestEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProfileEntity $profile = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $caller = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resource = null;

    #[ORM\Column]
    private ?int $mode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $clientIp = null;

    #[ORM\Column]
    private ?int $totalIntegrityRules = null;

    #[ORM\ManyToMany(targetEntity: RuleEntity::class)]
    private Collection $brokenIntegrityRules;

    #[ORM\OneToMany(mappedBy: 'request', targetEntity: ParameterEntity::class, orphanRemoval: true)]
    private Collection $parameters;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->brokenIntegrityRules = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfile(): ?ProfileEntity
    {
        return $this->profile;
    }

    public function setProfile(?ProfileEntity $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getCaller(): ?string
    {
        return $this->caller;
    }

    public function setCaller(string $caller): self
    {
        $this->caller = $caller;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function setClientIp(string $clientIp): self
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    public function getTotalIntegrityRules(): ?int
    {
        return $this->totalIntegrityRules;
    }

    public function setTotalIntegrityRules(int $totalIntegrityRules): self
    {
        $this->totalIntegrityRules = $totalIntegrityRules;

        return $this;
    }

    public function getBrokenIntegrityRules(): Collection
    {
        return $this->brokenIntegrityRules;
    }

    public function addBrokenIntegrityRule(RuleEntity $brokenIntegrityRule): self
    {
        if (!$this->brokenIntegrityRules->contains($brokenIntegrityRule)) {
            $this->brokenIntegrityRules->add($brokenIntegrityRule);
        }

        return $this;
    }

    public function removeBrokenIntegrityRule(RuleEntity $brokenIntegrityRule): self
    {
        $this->brokenIntegrityRules->removeElement($brokenIntegrityRule);

        return $this;
    }

    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function addParameter(ParameterEntity $parameter): self
    {
        if (!$this->parameters->contains($parameter)) {
            $this->parameters->add($parameter);
            $parameter->setRequest($this);
        }

        return $this;
    }

    public function removeParameter(ParameterEntity $parameter): self
    {
        if ($this->parameters->removeElement($parameter)) {
            if ($parameter->getRequest() === $this) {
                $parameter->setRequest(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

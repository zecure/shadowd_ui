<?php

namespace App\Entity\Domain;

use App\Repository\Domain\ProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'domain_profiles')]
class ProfileEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $key = null;

    #[ORM\Column]
    private ?int $mode = null;

    #[ORM\Column]
    private ?bool $floodProtectionEnabled = null;

    #[ORM\Column]
    private ?int $floodProtectionTime = null;

    #[ORM\Column]
    private ?int $floodProtectionThreshold = null;

    #[ORM\Column]
    private ?bool $anomalyAnalysisEnabled = null;

    #[ORM\Column]
    private ?bool $integrityAnalysisEnabled = null;

    #[ORM\Column]
    private ?bool $patternAnalysisEnabled = null;

    #[ORM\Column]
    private ?int $patternAnalysisThreshold = null;

    #[ORM\Column]
    private ?bool $cacheOutdated = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

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

    public function isFloodProtectionEnabled(): ?bool
    {
        return $this->floodProtectionEnabled;
    }

    public function setFloodProtectionEnabled(bool $floodProtectionEnabled): self
    {
        $this->floodProtectionEnabled = $floodProtectionEnabled;

        return $this;
    }

    public function getPatternRecognitionThreshold(): ?int
    {
        return $this->patternRecognitionThreshold;
    }

    public function setPatternRecognitionThreshold(int $patternRecognitionThreshold): self
    {
        $this->patternRecognitionThreshold = $patternRecognitionThreshold;

        return $this;
    }

    public function getFloodProtectionTime(): ?int
    {
        return $this->floodProtectionTime;
    }

    public function setFloodProtectionTime(int $floodProtectionTime): self
    {
        $this->floodProtectionTime = $floodProtectionTime;

        return $this;
    }

    public function getFloodProtectionThreshold(): ?int
    {
        return $this->floodProtectionThreshold;
    }

    public function setFloodProtectionThreshold(int $floodProtectionThreshold): self
    {
        $this->floodProtectionThreshold = $floodProtectionThreshold;

        return $this;
    }

    public function isAnomalyAnalysisEnabled(): ?bool
    {
        return $this->anomalyAnalysisEnabled;
    }

    public function setAnomalyAnalysisEnabled(bool $anomalyAnalysisEnabled): self
    {
        $this->anomalyAnalysisEnabled = $anomalyAnalysisEnabled;

        return $this;
    }

    public function isIntegrityAnalysisEnabled(): ?bool
    {
        return $this->integrityAnalysisEnabled;
    }

    public function setIntegrityAnalysisEnabled(bool $integrityAnalysisEnabled): self
    {
        $this->integrityAnalysisEnabled = $integrityAnalysisEnabled;

        return $this;
    }

    public function isPatternAnalysisEnabled(): ?bool
    {
        return $this->patternAnalysisEnabled;
    }

    public function setPatternAnalysisEnabled(bool $patternAnalysisEnabled): self
    {
        $this->patternAnalysisEnabled = $patternAnalysisEnabled;

        return $this;
    }

    public function getPatternAnalysisThreshold(): ?int
    {
        return $this->patternAnalysisThreshold;
    }

    public function setPatternAnalysisThreshold(int $patternAnalysisThreshold): self
    {
        $this->patternAnalysisThreshold = $patternAnalysisThreshold;

        return $this;
    }

    public function isCacheOutdated(): ?bool
    {
        return $this->cacheOutdated;
    }

    public function setCacheOutdated(bool $cacheOutdated): self
    {
        $this->cacheOutdated = $cacheOutdated;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

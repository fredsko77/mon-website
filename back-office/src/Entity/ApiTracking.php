<?php

namespace App\Entity;

use App\Repository\ApiTrackingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiTrackingRepository::class)]
class ApiTracking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'apiTrackings')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbCallApiRequest = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastRequestDate = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNbCallApiRequest(): ?int
    {
        return $this->nbCallApiRequest;
    }

    public function setNbCallApiRequest(?int $nbCallApiRequest): self
    {
        $this->nbCallApiRequest = $nbCallApiRequest;

        return $this;
    }

    public function getLastRequestDate(): ?\DateTimeImmutable
    {
        return $this->lastRequestDate;
    }

    public function setLastRequestDate(?\DateTimeImmutable $lastRequestDate): self
    {
        $this->lastRequestDate = $lastRequestDate;

        return $this;
    }
}

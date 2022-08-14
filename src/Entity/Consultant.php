<?php

namespace App\Entity;

use App\Repository\ConsultantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultantRepository::class)]
class Consultant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userlink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserlink(): ?User
    {
        return $this->userlink;
    }

    public function setUserlink(?User $userlink): self
    {
        $this->userlink = $userlink;

        return $this;
    }
}

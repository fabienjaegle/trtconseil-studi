<?php

namespace App\Entity;

use App\Repository\RecruiterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruiterRepository::class)]
class Recruiter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $companyname = null;

    #[ORM\Column(length: 255)]
    private ?string $companyaddress = null;

    #[ORM\Column(length: 5)]
    private ?string $companyzipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $companycity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userlink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyname(): ?string
    {
        return $this->companyname;
    }

    public function setCompanyname(string $companyname): self
    {
        $this->companyname = $companyname;

        return $this;
    }

    public function getCompanyaddress(): ?string
    {
        return $this->companyaddress;
    }

    public function setCompanyaddress(string $companyaddress): self
    {
        $this->companyaddress = $companyaddress;

        return $this;
    }

    public function getCompanyzipcode(): ?string
    {
        return $this->companyzipcode;
    }

    public function setCompanyzipcode(string $companyzipcode): self
    {
        $this->companyzipcode = $companyzipcode;

        return $this;
    }

    public function getCompanycity(): ?string
    {
        return $this->companycity;
    }

    public function setCompanycity(string $companycity): self
    {
        $this->companycity = $companycity;

        return $this;
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

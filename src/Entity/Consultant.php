<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\ConsultantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultantRepository::class)]
class Consultant extends User
{

}

<?php

namespace App\Controller\Recruiter;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RecruiterDashboardController extends AbstractController
{
    #[Route('/dashboard/recruiter', name: 'app_dashboard_recruiter_index')]
    public function index(): Response
    {
        return $this->render('recruiter/index.html.twig');
    }
}

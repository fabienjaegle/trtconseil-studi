<?php

namespace App\Controller\Candidate;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateDashboardController extends AbstractDashboardController
{
    #[Route('/dashboard/candidate', name: 'app_dashboard_candidate_index')]
    public function index(): Response
    {
        return $this->render('candidate/index.html.twig');
    }
}

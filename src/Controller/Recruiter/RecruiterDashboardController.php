<?php

namespace App\Controller\Recruiter;

use App\Entity\Recruiter;
use App\Form\RecruiterType;
use App\Repository\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class RecruiterDashboardController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/dashboard/recruiter', name: 'app_dashboard_recruiter_index')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        return $this->render('recruiter/index.html.twig', [
            'recruiter' => $user
        ]);
    }

    #[Route('/dashboard/recruiter/{id}/edit', name: 'app_dashboard_recruiter_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id, RecruiterRepository $recruiterRepository): Response
    {
        $recruiter = $recruiterRepository->find($id);

        $form = $this->createForm(RecruiterType::class, $recruiter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $recruiterRepository->add($recruiter, true);

            return $this->redirectToRoute('app_dashboard_recruiter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recruiter/edit.html.twig', [
            'recruiter' => $recruiter,
            'form' => $form,
        ]);
    }
}

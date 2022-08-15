<?php

namespace App\Controller\Consultant;

use App\Repository\CandidateRepository;
use App\Repository\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ConsultantDashboardController extends AbstractController
{
    #[Route('/dashboard/consultant', name: 'app_dashboard_consultant_index')]
    public function index(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository): Response
    {
        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }

    #[Route('/dashboard/consultant/approuve/{id}', name: 'app_dashboard_consultant_approuve_candidate', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuve(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        $candidate->setIsValidated(true);
        $entityManager->persist($candidate);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le compte a été approuvé.'
        );

        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }

    #[Route('/dashboard/consultant/reject/{id}', name: 'app_dashboard_consultant_reject_candidate', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function reject(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        $candidate->setIsValidated(false);
        $entityManager->persist($candidate);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le compte a été rejeté.'
        );

        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }
}

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

    #[Route('/dashboard/consultant/approuveCandidate/{id}', name: 'app_dashboard_consultant_approuve_candidate', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuveCandidate(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        $candidate->setIsValidated(true);
        $entityManager->persist($candidate);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le compte candidat a été approuvé.'
        );

        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }

    #[Route('/dashboard/consultant/rejectCandidate/{id}', name: 'app_dashboard_consultant_reject_candidate', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rejectCandidate(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        $candidate->setIsValidated(false);
        $entityManager->persist($candidate);
        $entityManager->flush();

        $this->addFlash(
            'error',
            'Le compte candidat a été rejeté.'
        );

        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }

    #[Route('/dashboard/consultant/approuveRecruiter/{id}', name: 'app_dashboard_consultant_approuve_recruiter', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuveRecruiter(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $recruiter = $recruiterRepository->find($id);

        $recruiter->setIsValidated(true);
        $entityManager->persist($recruiter);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le compte recruteur a été approuvé.'
        );

        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }

    #[Route('/dashboard/consultant/rejectRecruiter/{id}', name: 'app_dashboard_consultant_reject_recruiter', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rejectRecruiter(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $recruiter = $recruiterRepository->find($id);

        $recruiter->setIsValidated(false);
        $entityManager->persist($recruiter);
        $entityManager->flush();

        $this->addFlash(
            'error',
            'Le compte candidat a été rejeté.'
        );

        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null])
        ]);
    }
}

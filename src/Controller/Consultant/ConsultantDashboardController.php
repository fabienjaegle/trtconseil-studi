<?php

namespace App\Controller\Consultant;

use App\Repository\CandidateRepository;
use App\Repository\RecruiterRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ConsultantDashboardController extends AbstractController
{
    #[Route('/dashboard/consultant', name: 'app_dashboard_consultant_index')]
    public function index(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, JobOfferRepository $jobOfferRepository): Response
    {
        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null]),
            'joboffers' => $jobOfferRepository->findBy(['isValidated' => null])
        ]);
    }

    #[Route('/dashboard/consultant/approuveCandidate/{id}', name: 'app_dashboard_consultant_approuve_candidate', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuveCandidate(CandidateRepository $candidateRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        $candidate->setIsValidated(true);
        $entityManager->persist($candidate);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le compte candidat a été approuvé.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }

    #[Route('/dashboard/consultant/rejectCandidate/{id}', name: 'app_dashboard_consultant_reject_candidate', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rejectCandidate(CandidateRepository $candidateRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        $candidate->setIsValidated(false);
        $entityManager->persist($candidate);
        $entityManager->flush();

        $this->addFlash(
            'error',
            'Le compte candidat a été rejeté.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }

    #[Route('/dashboard/consultant/approuveRecruiter/{id}', name: 'app_dashboard_consultant_approuve_recruiter', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuveRecruiter(RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $recruiter = $recruiterRepository->find($id);

        $recruiter->setIsValidated(true);
        $entityManager->persist($recruiter);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le compte recruteur a été approuvé.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }

    #[Route('/dashboard/consultant/rejectRecruiter/{id}', name: 'app_dashboard_consultant_reject_recruiter', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rejectRecruiter(RecruiterRepository $recruiterRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $recruiter = $recruiterRepository->find($id);

        $recruiter->setIsValidated(false);
        $entityManager->persist($recruiter);
        $entityManager->flush();

        $this->addFlash(
            'error',
            'Le compte recruteur a été rejeté.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }

    #[Route('/dashboard/consultant/approuveOffer/{id}', name: 'app_dashboard_consultant_approuve_offer', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuveOffer(JobOfferRepository $jobOfferRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $jobOffer = $jobOfferRepository->find($id);

        $jobOffer->setIsValidated(true);
        $entityManager->persist($jobOffer);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'L\'annonce a été approuvée.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }

    #[Route('/dashboard/consultant/rejectCandidate/{id}', name: 'app_dashboard_consultant_reject_offer', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rejectOffer(JobOfferRepository $jobOfferRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $jobOffer = $jobOfferRepository->find($id);

        $jobOffer->setIsValidated(false);
        $entityManager->persist($jobOffer);
        $entityManager->flush();

        $this->addFlash(
            'error',
            'L\'annonce a été rejetée.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }
}

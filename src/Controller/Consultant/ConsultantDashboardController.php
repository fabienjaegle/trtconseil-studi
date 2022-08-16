<?php

namespace App\Controller\Consultant;

use App\Repository\CandidateRepository;
use App\Repository\RecruiterRepository;
use App\Repository\JobOfferRepository;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ConsultantDashboardController extends AbstractController
{
    #[Route('/dashboard/consultant', name: 'app_dashboard_consultant_index')]
    public function index(CandidateRepository $candidateRepository, RecruiterRepository $recruiterRepository, JobOfferRepository $jobOfferRepository, ApplicationRepository $applicationRepository): Response
    {
        return $this->render('consultant/index.html.twig', [
            'candidates' => $candidateRepository->findBy(['isValidated' => null]),
            'recruiters' => $recruiterRepository->findBy(['isValidated' => null]),
            'joboffers' => $jobOfferRepository->findBy(['isValidated' => null]),
            'applications' => $applicationRepository->findBy(['isValidated' => null])
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

    #[Route('/dashboard/consultant/rejectOffer/{id}', name: 'app_dashboard_consultant_reject_offer', methods: ['GET'], requirements: ['id' => '\d+'])]
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

    #[Route('/dashboard/consultant/approuveApplication/{id}', name: 'app_dashboard_consultant_approuve_application', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function approuveApplication(ApplicationRepository $applicationRepository, int $id, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $application = $applicationRepository->find($id);

        $application->setIsValidated(true);
        $entityManager->persist($application);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'La candidature a été approuvée.'
        );

        //SEND EMAIL
        $candidate = $application->getCandidate();
        $jobOffer = $application->getJobOffer();
        $recruiter = $jobOffer->getRecruiter();
        $recruiterEmail = $jobOffer->getRecruiter()->getEmail();
        
        $email = (new Email())
            ->from('contact@trtconseil.com')
            ->to($recruiterEmail)
            ->subject('Nouvelle candidature')
            ->html('
                <p>Bonjour '. $recruiter->getFirstname() .' '. $recruiter->getLastname() .',</p>
                <br>
                <p>Le candidat ' . $candidate->getFirstname() . ' ' . $candidate->getLastname() . ' a postulé à votre annonce pour le poste de "' . $jobOffer->getTitle() . '".</p>
                <br>
                <p>Nous vous avons transmis, en pièce jointe de ce mail, son CV.</p>
                <br>
                <p>Connectez-vous à votre espace recruteur afin d\'avoir plus d\'information sur le candidat.</p>
                <br>
                <br>
                <p>Cordialement,</p>
                <p>L\'équipe TRT Conseil.</p>
            ')
            ->attachFromPath($this->getParameter('cv_directory').'/'.$candidate->getCv(), 'CV', 'application/pdf')
        ;

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            $this->addFlash(
                'error',
                'Une erreur est survenue lors de l\'envoi de l\'email au recruteur.'
            );
        }

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }

    #[Route('/dashboard/consultant/rejectApplication/{id}', name: 'app_dashboard_consultant_reject_application', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rejectApplication(ApplicationRepository $applicationRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $application = $applicationRepository->find($id);

        $application->setIsValidated(false);
        $entityManager->persist($application);
        $entityManager->flush();

        $this->addFlash(
            'error',
            'La candidature a été rejetée.'
        );

        return $this->redirectToRoute('app_dashboard_consultant_index');
    }
}

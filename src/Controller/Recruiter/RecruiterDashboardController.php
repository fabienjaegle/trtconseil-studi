<?php

namespace App\Controller\Recruiter;

use App\Entity\Recruiter;
use App\Entity\JobOffer;
use App\Form\RecruiterType;
use App\Form\JobOfferType;
use App\Repository\RecruiterRepository;
use App\Repository\JobOfferRepository;
use App\Repository\ApplicationRepository;
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
    public function index(JobOfferRepository $jobofferRepository): Response
    {
        $user = $this->security->getUser();
        $joboffers = $jobofferRepository->findBy(['recruiter' => $user]);

        return $this->render('recruiter/index.html.twig', [
            'recruiter' => $user,
            'joboffers' => $joboffers
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

    #[Route('/dashboard/recruiter/new', name: 'app_dashboard_recruiter_new_joboffer', methods: ['GET', 'POST'])]
    public function new(Request $request, JobOfferRepository $jobofferRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $joboffer = new JobOffer();
        $recruiter = $this->security->getUser();
        $form = $this->createForm(JobOfferType::class, $joboffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $joboffer->setRecruiter($recruiter);

            $jobofferRepository->add($joboffer, true);
 
            $entityManager->persist($joboffer);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'annonce a été créée avec succès. Un consultant doit l\'approuver pour qu\'elle paraisse en ligne.'
            );

            return $this->redirectToRoute('app_dashboard_recruiter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joboffer/new.html.twig', [
            'joboffer' => $joboffer,
            'form' => $form,
        ]);
    }

    #[Route('/dashboard/recruiter/offer/{id}/show/candidates', name: 'app_dashboard_recruiter_show_candidates', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function showCandidates(JobOfferRepository $jobofferRepository, ApplicationRepository $applicationRepository, int $id, RecruiterRepository $recruiterRepository): Response
    {
        $jobOffer = $jobofferRepository->find($id);
        $applications = $applicationRepository->findByJobOffer($jobOffer);

        return $this->renderForm('recruiter/show_candidates.html.twig', [
            'applications' => $applications
        ]);
    }
}

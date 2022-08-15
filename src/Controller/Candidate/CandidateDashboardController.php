<?php

namespace App\Controller\Candidate;

use App\Entity\Consultant;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;

class CandidateDashboardController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/dashboard/candidate', name: 'app_dashboard_candidate_index')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        return $this->render('candidate/index.html.twig', [
            'candidate' => $user
        ]);
    }

    #[Route('/dashboard/candidate/{id}/edit', name: 'app_dashboard_candidate_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id, CandidateRepository $candidateRepository, SluggerInterface $slugger): Response
    {
        $candidate = $candidateRepository->find($id);

        if ($candidate->getCv() !== null) {
            $candidate->setCv(
                new File($this->getParameter('cv_directory').'/'.$candidate->getCv())
            );
        }

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cvFile = $form->get('cvFilename')->getData();

            //We have a CV file, process it!
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                // Move the file to the directory where CV are stored
                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the filename property to store the PDF file name
                // instead of its contents
                $candidate->setCv($newFilename);
            }

            $candidateRepository->add($candidate, true);

            return $this->redirectToRoute('app_dashboard_candidate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }
}

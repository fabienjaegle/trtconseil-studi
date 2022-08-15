<?php

namespace App\Controller\Admin;

use App\Entity\Consultant;
use App\Form\ConsultantType;
use App\Repository\ConsultantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard/admin', name: 'app_dashboard_admin')]
    public function index(ConsultantRepository $consultantRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'consultants' => $consultantRepository->findAll(),
        ]);
    }

    #[Route('/dashboard/admin/new', name: 'app_create_consultant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultantRepository $consultantRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $consultant = new Consultant();
        $form = $this->createForm(ConsultantType::class, $consultant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultantRepository->add($consultant, true);
 
            // encode the password
            $consultant->setPassword(
            $userPasswordHasher->hashPassword(
                    $consultant,
                    $form->get('password')->getData()
                )
            );

            // Set the role
            $consultant->setRoles(['ROLE_CONSULTANT']);
            $entityManager->persist($consultant);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'consultant' => $consultant,
            'form' => $form,
        ]);
    }

    #[Route('/dashboard/admin/consultant/{id}', name: 'display_consultant', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(ConsultantRepository $consultantRepository, int $id): Response
    {
        $consultant = $consultantRepository->find($id);

        return $this->render('admin/display.html.twig', [
            'consultant' => $consultant,
        ]);
    }

    #[Route('/dashboard/admin/consultant/{id}/edit', name: 'edit_consultant', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id, ConsultantRepository $consultantRepository): Response
    {
        $consultant = $consultantRepository->find($id);

        $form = $this->createForm(ConsultantType::class, $consultant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultantRepository->add($consultant, true);

            return $this->redirectToRoute('app_dashboard_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'consultant' => $consultant,
            'form' => $form,
        ]);
    }

    #[Route('/dashboard/admin/consultant/{id}', name: 'app_create_consultant_delete', methods: ['POST'])]
    public function delete(Request $request, Consultant $consultant, ConsultantRepository $consultantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultant->getId(), $request->request->get('_token'))) {
            $consultantRepository->remove($consultant, true);
        }

        return $this->redirectToRoute('app_dashboard_admin', [], Response::HTTP_SEE_OTHER);
    }
}

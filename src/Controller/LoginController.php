<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = "";
        $error = "";

        if ($this->getUser()) {
            if ($this->getUser()->isVerified()) {
                return $this->redirectToRoute('app_home');

                // get the login error if there is one
                $error = $authenticationUtils->getLastAuthenticationError();

                $lastUsername = $authenticationUtils->getLastUsername();
            }
            else
                $error = "L'utilisateur n'est pas encore vérifié par un consultant. Connexion impossible pour le moment.";
        }

        return $this->render('login/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

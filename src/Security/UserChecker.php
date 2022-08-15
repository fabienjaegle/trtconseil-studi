<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // Check if the user is a candidate or a recruiter to test the validation function.
        if (!$user instanceof Candidate || !$user instanceof Recruiter) {
            return;
        }

        // validation=null: the user has not been approuved yet.
        if ($user->isValidated() === null) {
            throw new CustomUserMessageAccountStatusException('Le compte n\'a pas encore été approuvé.');
        }

        //validation=false: the user has been rejected by a consultant.
        if ($user->isValidated() === false) {
            throw new CustomUserMessageAccountStatusException('Le compte a été rejeté par un consultant. Connexion impossible à la plateforme.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }
}
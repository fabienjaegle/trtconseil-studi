<?php

namespace App\Controller\Admin;

use App\Entity\Consultant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ConsultantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Consultant::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $consultant = new Consultant();
        $consultant->setRoles(['ROLE_CONSULTANT']);

        return $consultant;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            TextField::new('password')->setFormType(PasswordType::class),
            TextField::new('firstname'),
            TextField::new('lastname'),
            EmailField::new('email'),
        ];
    }
}

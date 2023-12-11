<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('formation')->autocomplete(),
            DateField::new('start', 'Début de session'),
            DateField::new('end', 'Fin de session'),
            IntegerField::new('places', 'Places restantes'),
            AssociationField::new('courses', 'Cours')->setFormTypeOption('multiple', true),
            AssociationField::new('user', 'Participants')->setFormTypeOption('multiple', true)
        ];
    }
}

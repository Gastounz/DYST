<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa-solid fa-trash')->setLabel(false);
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', 'Pseudonyme'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            TextField::new('adress', 'Adresse'),
            IntegerField::new('post_code', 'CP'),
            TextField::new('city', 'Ville'),
            TextField::new('email', 'E-mail'),
            TelephoneField::new('phone', 'Téléphone'),

            // Utilisation des options d'ImageField plutôt que du service PictureUploader pour un résultat équivalent
            ImageField::new('picture', 'Photo de profil')
                ->setUploadDir('public/profiles')
                ->setUploadedFileNamePattern(
                    fn (UploadedFile $file): string => sprintf('profiles/%s.%s', bin2hex(random_bytes(20)), $file->guessExtension())
                ),

            AssociationField::new('sessions', 'Formations suivies')->setFormTypeOptions(['multiple' => true, 'by_reference' => false])
        ];
    }
}

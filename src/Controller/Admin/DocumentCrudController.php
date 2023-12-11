<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\FileField;
use App\Entity\Document;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Document::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('course', 'Cours')->autocomplete(),
            TextField::new('name', 'Nom du document')->hideOnForm()->setEmptyData(''),
            FileField::new('document', 'Document')->onlyOnForms() //->setUploadDir('public/documents')->setUploadedFileNamePattern(fn (UploadedFile $file): string => sprintf('documents/%s.%s', bin2hex(random_bytes(20)), $file->guessExtension()))
        ];
    }
}

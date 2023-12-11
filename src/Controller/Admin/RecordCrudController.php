<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\FileField;
use App\Entity\Record;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Record::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('course', 'Cours')->autocomplete(),
            TextField::new('name', 'Nom de l\'enregistrement')->hideOnForm(),
            FileField::new('record', 'Enregistrement')->onlyOnForms() //->setUploadDir('public/records')->setUploadedFileNamePattern(fn (UploadedFile $file): string => sprintf('records/%s.%s', bin2hex(random_bytes(20)), $file->guessExtension()))
        ];
    }
}

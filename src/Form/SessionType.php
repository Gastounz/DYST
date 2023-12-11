<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateType::class, ['label' => 'Début de formation'])
            ->add('end', DateType::class, ['label' => 'Fin de formation'])
            ->add('places', IntegerType::class, ['label' => 'Places restantes'])
            ->add('formation', EntityType::class, ['label' => 'Formation', 'class' => Formation::class, 'choice_label' => 'name'])
            ->add('user', CollectionType::class, ['label' => 'Stagiaires']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

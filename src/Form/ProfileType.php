<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'empty_data' => 'hello'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => false,

            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => 'Image de profil',
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'maxSizeMessage' => "Votre image fait {{ size }} {{ suffix }}. La limite est de {{ limit }} {{ suffix }}",
                    ])
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('post_code', IntegerType::class, [
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}

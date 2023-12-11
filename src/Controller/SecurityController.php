<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Services\PictureUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, PictureUploader $pictureUploader): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $hash = $passwordHasher->hashPassword($user, $userForm->get('plainPassword')->getData());
            $user->setPassword($hash);

            if ($userForm->isSubmitted() && $userForm->isValid()) {

                $picture = $userForm->get('picture')->getData();

                if ($picture) {

                    $path = $pictureUploader->upload($picture);
                    $user->setPicture($path);
                }
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Bienvenue dans l\'école DYST !');

            // Envoi de l'email de validation
            $email = new TemplatedEmail();
            $email->to($user->getEmail())
                ->subject('Bienvenue sur DYST')
                ->htmlTemplate('@email_templates/welcome.html.twig')
                ->context([
                    'username' => $user->getUsername()
                ]);

            $mailer->send($email);

            return $this->redirectToRoute(('signin'));
        }

        return $this->render('security/signup.html.twig', ['form' => $userForm->createView()]);
    }

    #[Route('/signin', name: 'signin')]
    public function signin(AuthenticationUtils $authenticationUtils): Response
    {
        // Renvoie vers la page d'accueil si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $username = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/signin.html.twig', [
            'username' => $username,
            'error' => $error
        ]);
    }

    #[Route('/profile', name: 'profile')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function profile(Request $request, EntityManagerInterface $em, PictureUploader $pictureUploader)
    {
        $user = $this->getUser();
        $userForm = $this->createForm(ProfileType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $picture = $userForm->get('picture')->getData();

            if ($picture) {

                $path = $pictureUploader->upload($picture);
                $user->setPicture($path);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succès !');

            return $this->redirectToRoute(('profile'));
        }

        return $this->render('user/profile.html.twig', ['form' => $userForm->createView()]);
    }

    #[Route('/new_password', name: 'new_password')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function newPassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em,)
    {
        $user = $this->getUser();

        $passwordForm = $this->createForm(NewPasswordType::class);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            if ($passwordHasher->isPasswordValid($user, $passwordForm->get('currentPassword')->getData())) {

                $encodedPassword = $passwordHasher->hashPassword(
                    $user,
                    $passwordForm->get('plainPassword')->getData()
                );

                $user->setPassword($encodedPassword);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès !');

                return $this->redirectToRoute(('profile'));
            } else {
                $this->addFlash('success', 'Votre mot de passe est erroné.');
            }
        }

        return $this->render('reset_password/new_password.html.twig', ['form' => $passwordForm->createView()]);
    }

    // Le logout est géré automatiquement par form
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }
}

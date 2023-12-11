<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CommentRepository;
use App\Repository\FormationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, MailerInterface $mailer, FormationRepository $formationsRepo, CommentRepository $commentRepo, UserInterface $user): Response
    {
        $formations = $formationsRepo->findAll();

        $comments = $commentRepo->findAll();
        shuffle($comments);
        $comments = array_slice($comments, 0, 3);

        $contact = new Contact();

        if ($user) {
            $form = $this->createForm(ContactType::class, $contact)
                ->remove('firstname')
                ->remove('lastname')
                ->remove('email');

            $contact->setFirstname($user->getFirstName());
            $contact->setLastname($user->getLastname());
            $contact->setEmail($user->getEmail());
        } else {
            $form = $this->createForm(ContactType::class, $contact);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Envoi de l'email
            $email = (new TemplatedEmail())
                ->htmlTemplate('@email_templates/message.html.twig')
                ->from($contact->getEmail())
                ->to('raphael-braillard@outlook.fr')
                ->subject('Demande de ' . $contact->getFirstname() . ' ' . $contact->getLastname())
                ->context([
                    'firstname' => $contact->getFirstname(),
                    'lastname' => $contact->getLastname(),
                    'message' => $contact->getMessage()
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre e-mail a été envoyé avec succès !');

            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', ['formations' => $formations, 'comments' => $comments, 'form' => $form->createView()]);
    }

    // #[Route('/email', name: 'email')]
    // public function sendEmail(MailerInterface $mailer): Response
    // {
    //     $email = (new Email())
    //         ->from('raphael-braillard@outlook.fr')
    //         ->to('raphael-braillard@outlook.fr')
    //         ->subject('Hello world')
    //         ->text('Ceci est un email (oui oui, sérieux)');

    //     $mailer->send($email);

    //     return $this->render('home/index.html.twig');
    // }
}

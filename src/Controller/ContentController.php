<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\Session;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ContentController extends AbstractController
{
    #[Route('/content', name: 'content')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function index(CommentRepository $commentRepo, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Ajout d'un commentaire à la base de données
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($user)
                ->setDate(new \DateTimeImmutable());

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');

            return $this->redirectToRoute('content');
        }

        return $this->render('content/content.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/session/{id}', name: 'session')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function session(Session $session, Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('content/session.html.twig', ['session' => $session]);
    }

    #[Route('/course/{id}', name: 'course')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function course(Course $course, Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('content/course.html.twig', ['course' => $course]);
    }
}

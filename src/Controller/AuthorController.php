<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/authorName/{name?}', name: 'Show_author')]
    public function showAuthor(?string $name): Response // Ajout de ?string pour $name optionnel
    {
        return $this->render('/author/show.html.twig', [
            'author_name' => $name
        ]);
    }

    #[Route('/authors', name: 'authors_list')]
    public function list(): Response
    {
        $authors = [
            ['id' => 1, 'picture' => 'assets/images/Victor_hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['id' => 2, 'picture' => 'assets/images/William Shakespeare.jpeg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['id' => 3, 'picture' => 'assets/images/Taha Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        return $this->render('author/list.html.twig', ['authors' => $authors,]);
    }

    #[Route('/authors/details/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {
        // Données statiques (normalement, vous utiliseriez un Repository ici)
        $authors = [
            ['id' => 1, 'picture' => 'assets/images/Victor_hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['id' => 2, 'picture' => 'assets/images/William Shakespeare.jpeg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['id' => 3, 'picture' => 'assets/images/Taha Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        $author = array_filter($authors, fn($a) => $a['id'] == $id);
        $author = array_values($author)[0] ?? null;

        if ($author) {
            return $this->render('author/show.html.twig', [
                'author' => $author,
            ]);
        }
        return new Response("Auteur non trouvé", 404);
    }

    #[Route('/ShowAllAuthors', name: 'ShowAllAuthors')]
    public function ShowAllAuthors(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();
        return $this->render('author/ShowAllAuthors.html.twig', [
            'list' => $authors
        ]);
    }

    #[Route('/author/add_static', name: "author_add_static")]
    public function Add(ManagerRegistry $doctrine): Response // Ajout du type de retour
    {
        $em = $doctrine->getManager();
        $author = new Author();
        $author->setUsername('Test');
        $author->setEmail('test@esprit.tn');
        $author->setAge("25"); // L'âge est un string dans votre entité
        $em->persist($author);
        $em->flush();
        return new Response('Author added successfully');
    }

    #[Route('/author/add', name: 'author_add_form')]
    public function addForm(ManagerRegistry $doctrine, Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->add('Add', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('ShowAllAuthors');
        }

        return $this->render('author/add.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/author/edit/{id}', name: "author_edit")]
    public function update(ManagerRegistry $doctrine, $id, AuthorRepository $repo): Response // Ajout du type de retour
    {
        $em = $doctrine->getManager();
        $author = $repo->find($id);
        if ($author) {
            $author->setUsername("omar");
            $author->setEmail("omar.omar@esprit.com");
            $author->setAge("21"); // L'âge est un string
            $em->flush();
            return new Response("author mis a jour ");
        }
        return new Response("author non valide !!");
    }

    #[Route('/author/delete/{id}', name: 'author_delete')]
    public function Delete(ManagerRegistry $doctrine, $id, AuthorRepository $repo): Response // Ajout du type de retour
    {
        $em = $doctrine->getManager();
        $author = $repo->find($id);
        if ($author) {
            $em->remove($author);
            $em->flush();
            return $this->redirectToRoute('ShowAllAuthors');
        }
        return new Response("impossible de supprimer cet autheur !");
    }
}
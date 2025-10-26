<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository; // Correction : AuthorTpRepository n'était pas utilisé et probablement incorrect
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook', name: 'addBook')]
    public function addBook(ManagerRegistry $doctrine, Request $request): Response // Ajout du type de retour
    {
        $book = new Book();
        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->add('add', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $author = $book->getAuthor();
            if ($author) {
                // La logique de nb_books est gérée par getNbBooks() dans l'entité Author
                // $author->setNbBooks($author->getNbBooks() + 1); // Ceci est incorrect
                $em->persist($author); // Persistez l'auteur s'il est nouveau ou modifié
            }

            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showBooks');
        }
        return $this->render('book/add.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/showBooks', name: 'showBooks')]
    public function showBooks(BookRepository $repo): Response
    {
        $publishedBooks = $repo->findBy(['published' => true]);
        $unpublishedBooks = $repo->findBy(['published' => false]);

        $totalPublished = count($publishedBooks);
        $totalUnpublished = count($unpublishedBooks);

        return $this->render('book/show.html.twig', [
            'list' => $publishedBooks,
            'totalPublished' => $totalPublished,
            'totalUnpublished' => $totalUnpublished,
        ]);
    }

    #[Route('/editBook/{id}', name: 'editBook')]
    public function editBook(ManagerRegistry $doctrine, $id, BookRepository $repo, Request $request): Response // Ajout du type de retour
    {
        $book = $repo->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->add('edit', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('showBooks');
        }
        return $this->render('book/add.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/deleteBook/{id}', name: 'deleteBook')]
    public function deleteBook(ManagerRegistry $doctrine, $id, BookRepository $repo): Response // Ajout du type de retour
    {
        $book = $repo->find($id);
        $em = $doctrine->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('showBooks');
    }
/*
    #[Route('/NombreBookRomance', name: 'NombreBookRomance')]
    public function NombreBookRomance(ManagerRegistry $doctrine): Response // Ajout de ManagerRegistry et type de retour
    {
        $em = $doctrine->getManager(); // CORRIGÉ : Utilisation de ManagerRegistry
        
        // CORRIGÉ : Syntaxe DQL correcte (App\Entity\Book au lieu de src\Entity\Book)
        $req = $em->createQuery('SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.category = :cat');
        $req->setParameter('cat', 'Romance');
        $totalRomanceBooks = $req->getSingleScalarResult();

        // CORRIGÉ : Ajout d'une réponse pour afficher le résultat
        return new Response('Nombre de livres Romance : ' . $totalRomanceBooks);
    }
        */
}
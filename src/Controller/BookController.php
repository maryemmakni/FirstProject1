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

   public function showBooks(BookRepository $repo, Request $request): Response
    {
        // 1. Récupérer le terme de recherche depuis l'URL (?search=...)
        $search = $request->query->get('search');
        $list = [];
        $totalUnpublished = 0;

        // DÉBUT DE LA LOGIQUE D'INTÉGRATION DE LA RECHERCHE
        if ($search) {
            // S'il y a un terme de recherche, on utilise la fonction DQL
            $list = $repo->searchBookByAuthorDQL($search);
            // On réinitialise les totaux non publiés pour cette vue
            $totalPublished = count($list);
            $totalUnpublished = 0; // Pas pertinent lors d'une recherche

        } else {
            // SINON, on exécute votre logique originale (affichage normal)
            $list = $repo->findBy(['published' => true]);
            $unpublishedBooks = $repo->findBy(['published' => false]);

            $totalPublished = count($list);
            $totalUnpublished = count($unpublishedBooks);
        }
        // FIN DE LA LOGIQUE D'INTÉGRATION DE LA RECHERCHE


        return $this->render('book/show.html.twig', [
            // 'list' contient soit les livres publiés, soit les résultats de la recherche
            'list' => $list, 
            'totalPublished' => $totalPublished,
            'totalUnpublished' => $totalUnpublished,
            // On renvoie la valeur de recherche pour réafficher le formulaire
            'search_term' => $search
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
    }*/
     public function searchBooksByDate(BookRepository $repo): Response
    {
        // Les dates de la question
        $startDate = '2014-01-01';
        $endDate = '2018-12-31';

        // On appelle la fonction du Repository que vous avez créée
        $list = $repo->findBooksPublishedBetween($startDate, $endDate);

        // On réutilise le template d'affichage 'show.html.twig'
        return $this->render('book/show.html.twig', [
            'list' => $list,
            'totalPublished' => count($list), // Le total est juste le nombre de livres trouvés
            'totalUnpublished' => 0 // Cette variable n'est pas pertinente ici
        ]);
    }

        
}
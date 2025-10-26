<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    // Route d'index de démonstration
    #[Route('/author/index', name: 'app_author_index')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    // Affiche un auteur par nom (Route de démonstration)
    #[Route('/authorName/{name?}', name: 'Show_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig',[
            'author_name'=>$name
        ]);
    }

    // Affiche la liste de tous les auteurs (avec données Doctrine)
    #[Route('/ShowAllAuthors', name: 'ShowAllAuthors')]
    public function ShowAllAuthors(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();
        // Le template est placé dans le dossier 'author/'
        return $this->render('author/ShowAllAuthors.html.twig', [
            'list' => $authors
        ]);
    }

    // Ajout d'un auteur via code (Route de démonstration/test)
    #[Route('/add', name: "add")]
    public function Add(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $author = new Author();
        $author->setUsername('Test DQL');
        $author->setEmail('test.dql@esprit.tn');
        // NOTE : Supprimé $author->setAge(25); car l'attribut age n'existe plus dans l'entité.
        
        $em->persist($author);
        $em->flush();
        return new Response('Author added successfully (via code)!');
    }

    // Modification d'un auteur via code (Route de démonstration/test)
    #[Route('/modify/{id}', name: "modify")]
    public function update(ManagerRegistry $doctrine, $id, AuthorRepository $repo): Response
    {
        $em = $doctrine->getManager();
        $author = $repo->find($id);
        
        if ($author) {
            $author->setUsername("Omar Mis à Jour");
            $author->setEmail("omar.omar@esprit.com");
            // NOTE : Supprimé $author->setAge(21); car l'attribut age n'existe plus dans l'entité.
            $em->flush();
            return new Response("Auteur mis à jour !");
        }
        return new Response("Auteur non valide !!", 404);
    }

    // Suppression d'un auteur
    #[Route('/delete/{id}', name: 'delete')]
    public function Delete(ManagerRegistry $doctrine, $id, AuthorRepository $repo): Response
    {
        $em = $doctrine->getManager();
        $author = $repo->find($id);
        
        if ($author) {
            $em->remove($author);
            $em->flush();
            $this->addFlash('success', 'Auteur supprimé avec succès.');
            return $this->redirectToRoute('ShowAllAuthors');
        }
        return new Response("Impossible de supprimer cet auteur ! ID non trouvé.", 404);
    }

    // Affichage des détails d'un auteur trouvé via Doctrine
    #[Route('/showAuthor2/{id}', name: 'showAuthor')]
    public function showAuthor2(AuthorRepository $repo, $id): Response
    {
        $author = $repo->find($id);
        return $this->render('author/ShowDetailsAuthor.html.twig', [
            'author' => $author
        ]);
    }

    // Ajout d'un auteur via Formulaire
    #[Route('/addForm', name: 'addForm')]
    public function addForm(ManagerRegistry $doctrine, Request $request): Response
    {
        $author = new Author();
        // Utilisation de AuthorType pour construire le formulaire
        $form = $this->createForm(AuthorType::class, $author);
        $form->add('Add', SubmitType::class, ['label' => 'Sauvegarder']); // Bouton du formulaire
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            $this->addFlash('success', 'Auteur ajouté avec succès !');
            return $this->redirectToRoute('ShowAllAuthors');
        }

        return $this->render('author/add.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
    
    // NOUVELLE MÉTHODE DEMANDÉE : Suppression des auteurs sans livre
    #[Route('/authors/delete-empty', name: 'deleteAuthorsWithoutBooks')]
    public function deleteAuthorsWithoutBooks(AuthorRepository $authorRepository): Response
    {
        $deletedCount = $authorRepository->deleteAuthorsWithZeroBooks();

        $message = "Opération terminée : **{$deletedCount}** auteur(s) sans livre ont été supprimés.";
        $this->addFlash('info', $message);

        return $this->redirectToRoute('ShowAllAuthors');
    }

    // Routes de démonstration/Mockup (maintenues pour votre référence)

    #[Route('/authors', name: 'authors_list')]
    public function list(): Response
    {
        $authors = [
             ['id' => 1, 'picture' => 'assets/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
             ['id' => 2, 'picture' => 'assets/images/william-shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
             ['id' => 3, 'picture' => 'assets/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/authors/details/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {
        $authors = [
             ['id' => 1, 'picture' => 'assets/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
             ['id' => 2, 'picture' => 'assets/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
             ['id' => 3, 'picture' => 'assets/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        $author = array_filter($authors, fn($a) => $a['id'] == $id);
        $author = array_values($author)[0] ?? null;

        if ($author) {
             return $this->render('author/showAuthor.html.twig', [
                 'author' => $author,
             ]);
        }
        return new Response("Auteur non trouvé", 404);
    }
    #[Route('/deleteAuthor/{id}', name: "deleteAuthor")]
    public function deleteAuthor($id,ManagerRegistry $doctrine, AuthorRepository $repo): Response
    {
        $author = $repo->find($id);
        $em = $doctrine->getManager();
        $em->remove($author);
        $em->flush();
        
        return $this->redirectToRoute('ShowAuthors');
    }
        // NOTE : La méthode de suppression en masse des auteurs sans livres peut être ajoutée ici si nécessaire :
    // Liste des auteurs triés par email (utilise une méthode personnalisée du Repository)
    #[Route('/ListAuthorsByEmail', name:'ListAuthorsByEmail')]
    public function ListAuthorsByEmail ( AuthorRepository $repo ): Response
    {
        $authors = $repo->ListAuthorsByEmail();
        return $this->render('author/ShowAuthors.html.twig',[
            'list'=>$authors
        ]);
    }

    
}

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
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/authorName/{name?}', name: 'Show_author')]
    public function showAuthor($name): Response
    {
        return $this->render('/author/show.html.twig',[
            'author_name'=>$name
        ]);

    }
        
    

    

    
    #[Route('/authors', name: 'authors_list')]
    public function list(): Response
{
    $authors = [
        ['id' => 1, 'picture' => 'assets/images/Victor_hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => 'assets/images/William Shakespeare.jpeg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => 'assets/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];

    return $this->render('author/list.html.twig', [
        'authors' => $authors,
    ]);
}

    #[Route('/authors/details/{id}', name: 'author_details')]
    public function authorDetails($id): Response
{
    $authors = [
        ['id' => 1, 'picture' => 'assets/images/Victor_hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => 'assets/images/William Shakespeare.jpeg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => 'assets/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
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





}



    


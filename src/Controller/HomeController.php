<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;//Importe la classe AbstractController pour pouvoir l’utiliser sans écrire le namespace complet à chaque fois. abstractController est une classe fournie par Symfony qui offre des helpers pratiques (render(), redirectToRoute(), json(), addFlash(), etc.).
use Symfony\Component\HttpFoundation\Response;//Importe la classe Response (l’objet représentant une réponse HTTP). On l’utilise pour renvoyer du contenu au navigateur.
use Symfony\Component\Routing\Annotation\Route;//Importe la classe/attribut Route (ici utilisée comme attribute PHP 8) pour définir le routage directement au-dessus de la méthode.

class HomeController extends AbstractController //extends AbstractController signifie que cette classe hérite des méthodes et propriétés de AbstractController (accéder rapidement aux fonctionnalités Symfony)
{
    #[Route('/home', name: 'app_home')]//name: 'app_home' → nom unique de la route //routage 
    public function index(): Response //Déclare une méthode publique index.(): Response indique le type de retour attendu : un objet Response
    {
        return new Response("Bonjour mes étudiants");//Crée une instance de Response avec le contenu texte "Bonjour mes étudiants" et la renvoie au client.
    }
}

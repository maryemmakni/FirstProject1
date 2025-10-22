<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_show_service')] //définit une route Symfony , /service/{name} → URL de la route avec un paramètre dynamique name. //name: 'app_show_service' → nom unique de la route utilisé pour la redirection ou les liens internes.
    public function showService(string $name): Response //:Response → indique que la méthode renverra un objet de type Response.
    {
        return $this->render('service/showService.html.twig', [//render() prépare et renvoie une réponse HTML à partir d’un template Twig.(herité de abstarct)
            // On affiche la page Twig avec la variable "name"

            'name' => $name
        ]);
    }
     #[Route('/service/go-to-index', name: 'app_go_to_index')]
    public function goToIndex(): Response
    {
        // Redirection vers la route "app_home" de HomeController
        return $this->redirectToRoute('app_home');//'app_home' → nom de la route vers laquelle on redirige, ici la route définie dans HomeController.
    }
}

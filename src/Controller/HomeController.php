<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Effectue la requête HTTP vers l'API
        $response = $this->client->request('GET', 'https://gallotta.fr/wishlist/wishlist-back/api/wishlists');
        
        // Vérifie si la réponse est valide (statut HTTP 200)
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch wishlists.');
        }

        // Récupère le contenu de la réponse et décode le JSON
        $content = $response->getContent();
        $wishlists = json_decode($content, true);

        // Affiche les données dans la vue
        return $this->render('home/index.html.twig', [
            'wishlists' => $wishlists,
        ]);
    }
}
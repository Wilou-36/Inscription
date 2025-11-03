<?php
// Fichier : src/Controller/AccueilController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    // Définit la route racine du site
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        // Rend le nouveau template d'accueil
        return $this->render('accueil/index.html.twig', [
            // Aucune variable dynamique nécessaire pour cette page
        ]);
    }
}
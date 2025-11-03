<?php
// Fichier : src/Controller/InscriptionController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    // Définit la route pour la page d'inscription en BTS SIO
    #[Route('/inscription/bts-sio', name: 'app_inscription_bts')]
    public function index(): Response
    {
        // Définition des étapes du processus d'inscription
        $etapes = [
            ['id' => 1, 'titre' => 'Informations Personnelles', 'statut' => 'actif'],
            ['id' => 2, 'titre' => 'Parcours et Vœux', 'statut' => 'en_attente'],
            ['id' => 3, 'titre' => 'Téléchargement des Documents', 'statut' => 'en_attente'],
            ['id' => 4, 'titre' => 'Validation et Confirmation', 'statut' => 'en_attente'],
        ];

        // 🚨 VARIABLE MANQUANTE AJOUTÉE/ASSURÉE ICI POUR CORRIGER L'ERREUR RUNTIME (Image 2)
        $currentStepId = 1;

        return $this->render('inscription/index.html.twig', [
            'temps_estime' => '15 minutes', 
            'demarche_dematerialisee' => true,
            'etapes_inscription' => $etapes,
            'current_step_id' => $currentStepId // Ceci corrige l'erreur à la ligne 82 du template
        ]);
    }
}
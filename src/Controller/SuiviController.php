<?php

namespace App\Controller;

use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiviController extends AbstractController
{
    #[Route('/suivi', name: 'app_suivi')]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        // Vérifier que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $utilisateur = $this->getUser();
        
        // Récupérer le dossier d'inscription de l'utilisateur
        $etudiant = $etudiantRepository->findOneBy(['utilisateur' => $utilisateur]);
        
        return $this->render('suivi/index.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }
}

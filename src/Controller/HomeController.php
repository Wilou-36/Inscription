<?php

namespace App\Controller;

use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        $hasInscription = false;
        
        if ($this->getUser()) {
            $etudiant = $etudiantRepository->findOneBy(['utilisateur' => $this->getUser()]);
            if ($etudiant && $etudiant->getIdEtudiant()) {
                $hasInscription = true;
            }
        }
        
        return $this->render('home/index.html.twig', [
            'hasInscription' => $hasInscription,
        ]);
    }

    #[Route('/documents', name: 'app_documents')]
    public function documents(): Response
    {
        return $this->render('home/documents.html.twig');
    }

    #[Route('/formations', name: 'app_formations')]
    public function formations(): Response
    {
        return $this->render('home/formations.html.twig');
    }

    #[Route('/aide', name: 'app_aide')]
    public function aide(): Response
    {
        return $this->render('home/aide.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('home/mentions_legales.html.twig');
    }

    #[Route('/donnees-personnelles', name: 'app_donnees_personnelles')]
    public function donneesPersonnelles(): Response
    {
        return $this->render('home/donnees_personnelles.html.twig');
    }

    #[Route('/accessibilite', name: 'app_accessibilite')]
    public function accessibilite(): Response
    {
        return $this->render('home/accessibilite.html.twig');
    }

    #[Route('/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('home/cgu.html.twig');
    }
}

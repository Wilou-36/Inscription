<?php

namespace App\Controller;

use App\Repository\EtudiantRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(EtudiantRepository $etudiantRepository): Response
    {
        $etudiants = $etudiantRepository->findAll();
        
        return $this->render('admin/dashboard.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }

    #[Route('/utilisateurs', name: 'app_admin_utilisateurs')]
    public function utilisateurs(UtilisateurRepository $utilisateurRepository, EtudiantRepository $etudiantRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();
        
        // Pour chaque utilisateur, récupérer l'étudiant associé s'il existe
        $utilisateursData = [];
        foreach ($utilisateurs as $utilisateur) {
            $etudiant = $etudiantRepository->findOneBy(['utilisateur' => $utilisateur]);
            $utilisateursData[] = [
                'utilisateur' => $utilisateur,
                'etudiant' => $etudiant,
                'hasInscription' => $etudiant && $etudiant->getIdEtudiant(),
            ];
        }
        
        return $this->render('admin/utilisateurs.html.twig', [
            'utilisateursData' => $utilisateursData,
        ]);
    }

    #[Route('/inscriptions', name: 'app_admin_inscriptions')]
    public function inscriptions(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        // Récupérer les filtres depuis la requête
        $statutFiltre = $request->query->get('statut', 'tous');
        $btsFiltre = $request->query->get('bts', 'tous');
        
        // Construire la requête avec les filtres
        $queryBuilder = $etudiantRepository->createQueryBuilder('e')
            ->leftJoin('e.dossSco', 'd')
            ->leftJoin('d.scolarite', 's')
            ->addSelect('d', 's');
        
        // Appliquer le filtre de statut
        if ($statutFiltre !== 'tous') {
            $queryBuilder->andWhere('e.statut = :statut')
                ->setParameter('statut', $statutFiltre);
        }
        
        // Appliquer le filtre BTS
        if ($btsFiltre !== 'tous') {
            $queryBuilder->andWhere('s.classe LIKE :bts')
                ->setParameter('bts', '%' . $btsFiltre . '%');
        }
        
        $etudiants = $queryBuilder->getQuery()->getResult();
        
        return $this->render('admin/inscriptions.html.twig', [
            'etudiants' => $etudiants,
            'statutFiltre' => $statutFiltre,
            'btsFiltre' => $btsFiltre,
        ]);
    }

    #[Route('/inscription/{id}', name: 'app_admin_inscription_detail')]
    public function inscriptionDetail(int $id, EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = $etudiantRepository->find($id);
        
        if (!$etudiant) {
            throw $this->createNotFoundException('Inscription non trouvée');
        }
        
        return $this->render('admin/inscription_detail.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    #[Route('/inscription/{id}/valider', name: 'app_admin_inscription_valider', methods: ['POST'])]
    public function validerInscription(int $id, Request $request, EtudiantRepository $etudiantRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $etudiant = $etudiantRepository->find($id);
        
        if (!$etudiant) {
            throw $this->createNotFoundException('Inscription non trouvée');
        }
        
        $message = $request->request->get('message', '');
        
        $etudiant->setStatut('valide');
        $etudiant->setMessageAdmin($message ?: 'Votre dossier d\'inscription a été validé. Vous recevrez prochainement un email de confirmation avec les informations complémentaires.');
        
        $entityManager->flush();
        
        // Envoyer l'email de validation
        try {
            $email = (new TemplatedEmail())
                ->to($etudiant->getEmail())
                ->subject('Validation de votre inscription - Lycée Fulbert')
                ->htmlTemplate('email/validation.html.twig')
                ->context([
                    'etudiant' => $etudiant,
                    'dossier' => $etudiant->getDossSco(),
                    'message' => $etudiant->getMessageAdmin(),
                ]);
            
            $mailer->send($email);
        } catch (\Exception $e) {
            error_log('Erreur envoi email validation: ' . $e->getMessage());
        }
        
        $this->addFlash('success', 'L\'inscription a été validée avec succès. Un email a été envoyé à l\'étudiant.');
        
        return $this->redirectToRoute('app_admin_inscription_detail', ['id' => $id]);
    }

    #[Route('/inscription/{id}/refuser', name: 'app_admin_inscription_refuser', methods: ['POST'])]
    public function refuserInscription(int $id, Request $request, EtudiantRepository $etudiantRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $etudiant = $etudiantRepository->find($id);
        
        if (!$etudiant) {
            throw $this->createNotFoundException('Inscription non trouvée');
        }
        
        $message = $request->request->get('message', '');
        
        if (empty($message)) {
            $this->addFlash('error', 'Vous devez fournir un motif de refus.');
            return $this->redirectToRoute('app_admin_inscription_detail', ['id' => $id]);
        }
        
        $etudiant->setStatut('refuse');
        $etudiant->setMessageAdmin($message);
        
        $entityManager->flush();
        
        // Envoyer l'email de refus
        try {
            $email = (new TemplatedEmail())
                ->to($etudiant->getEmail())
                ->subject('Information concernant votre inscription - Lycée Fulbert')
                ->htmlTemplate('email/refus.html.twig')
                ->context([
                    'etudiant' => $etudiant,
                    'dossier' => $etudiant->getDossSco(),
                    'message' => $message,
                ]);
            
            $mailer->send($email);
        } catch (\Exception $e) {
            error_log('Erreur envoi email refus: ' . $e->getMessage());
        }
        
        $this->addFlash('success', 'L\'inscription a été refusée. Un email a été envoyé à l\'étudiant.');
        
        return $this->redirectToRoute('app_admin_inscription_detail', ['id' => $id]);
    }

    #[Route('/export-csv', name: 'app_admin_export_csv')]
    public function exportCsv(EtudiantRepository $etudiantRepository): Response
    {
        $etudiants = $etudiantRepository->createQueryBuilder('e')
            ->leftJoin('e.dossSco', 'd')
            ->leftJoin('d.scolarite', 's')
            ->addSelect('d', 's')
            ->getQuery()
            ->getResult();
        
        $csv = [];
        $csv[] = ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Date de naissance', 'BTS', 'Statut', 'Date inscription'];
        
        foreach ($etudiants as $etudiant) {
            $bts = $etudiant->getDossSco() && $etudiant->getDossSco()->getScolarite() 
                ? $etudiant->getDossSco()->getScolarite()->getClasse() 
                : 'N/A';
            
            $csv[] = [
                $etudiant->getIdEtudiant(),
                $etudiant->getNom(),
                $etudiant->getPrenom(),
                $etudiant->getEmail(),
                $etudiant->getTel(),
                $etudiant->getDateNaissance() ? $etudiant->getDateNaissance()->format('d/m/Y') : 'N/A',
                $bts,
                $etudiant->getStatut() ?? 'en_attente',
                $etudiant->getIdEtudiant() ? date('d/m/Y') : 'N/A',
            ];
        }
        
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="inscriptions_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://temp', 'r+');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
        
        foreach ($csv as $row) {
            fputcsv($output, $row, ';');
        }
        
        rewind($output);
        $response->setContent(stream_get_contents($output));
        fclose($output);
        
        return $response;
    }
}

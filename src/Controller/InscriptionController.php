<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Adresse;
use App\Entity\ResponsableLegal;
use App\Entity\Transport;
use App\Entity\DossSco;
use App\Entity\Scolarite;
use App\Entity\DocEtudiant;
use App\Entity\AnneeSco;
use App\Form\InscriptionType;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeScoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription/save-draft', name: 'app_inscription_save_draft', methods: ['POST'])]
    public function saveDraft(Request $request, EntityManagerInterface $entityManager, EtudiantRepository $etudiantRepository): JsonResponse
    {
        $session = $request->getSession();
        $data = json_decode($request->getContent(), true);
        
        if ($data) {
            // Convertir les dates en string pour la session
            foreach ($data as $key => $value) {
                if (is_object($value) && $value instanceof \DateTimeInterface) {
                    $data[$key] = $value->format('Y-m-d');
                }
            }
            
            $session->set('inscription_brouillon', $data);
            
            // Sauvegarder aussi dans la BDD si l'utilisateur a déjà un enregistrement étudiant
            if ($this->getUser()) {
                $etudiantBrouillon = $etudiantRepository->findOneBy(['utilisateur' => $this->getUser()]);
                if ($etudiantBrouillon) {
                    $etudiantBrouillon->setDraftJson(json_encode($data));
                    $entityManager->flush();
                }
            }
            
            return new JsonResponse(['success' => true, 'message' => 'Brouillon sauvegardé']);
        }
        
        return new JsonResponse(['success' => false, 'message' => 'Aucune donnée à sauvegarder'], 400);
    }
    
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request, EntityManagerInterface $entityManager, EtudiantRepository $etudiantRepository, AnneeScoRepository $anneeScoRepository, MailerInterface $mailer): Response
    {
        // Vérifier si les inscriptions sont ouvertes
        $anneeEnCours = $anneeScoRepository->findOneBy([], ['annee' => 'DESC']);
        
        if ($anneeEnCours && !$anneeEnCours->isInscriptionOuverte()) {
            $this->addFlash('error', 'Les inscriptions ne sont pas ouvertes actuellement.');
            
            if ($anneeEnCours->getDateOuverture() && $anneeEnCours->getDateOuverture() > new \DateTime()) {
                $this->addFlash('info', 'Les inscriptions ouvriront le ' . $anneeEnCours->getDateOuverture()->format('d/m/Y à H:i'));
            } elseif ($anneeEnCours->getDateFermeture() && $anneeEnCours->getDateFermeture() < new \DateTime()) {
                $this->addFlash('info', 'Les inscriptions ont fermé le ' . $anneeEnCours->getDateFermeture()->format('d/m/Y à H:i'));
            }
            
            return $this->redirectToRoute('app_home');
        }
        
        // Vérifier si l'utilisateur a déjà une inscription validée
        if ($this->getUser()) {
            $inscriptionExistante = $etudiantRepository->findOneBy([
                'utilisateur' => $this->getUser()
            ]);
            
            // Si une inscription existe et n'est pas un brouillon (a un ID et pas seulement draftJson)
            if ($inscriptionExistante && $inscriptionExistante->getIdEtudiant() && !$inscriptionExistante->getDraftJson()) {
                $this->addFlash('warning', 'Vous avez déjà soumis une inscription. Vous ne pouvez pas en créer une nouvelle.');
                return $this->redirectToRoute('app_suivi');
            }
        }
        
        // Récupérer le brouillon existant si disponible
        $session = $request->getSession();
        $brouillonData = $session->get('inscription_brouillon', null);
        
        // Si pas de brouillon en session, essayer de récupérer depuis la BDD
        if (!$brouillonData && $this->getUser()) {
            $etudiantExistant = $etudiantRepository->findOneBy(['utilisateur' => $this->getUser()]);
            if ($etudiantExistant && $etudiantExistant->getDraftJson()) {
                $brouillonData = json_decode($etudiantExistant->getDraftJson(), true);
                $session->set('inscription_brouillon', $brouillonData);
            }
        }
        
        $etudiant = new Etudiant();
        $form = $this->createForm(InscriptionType::class, $etudiant);
        
        $form->handleRequest($request);
        
        // Gérer la sauvegarde du brouillon
        if ($request->request->has('save_draft')) {
            // Sauvegarder toutes les données du formulaire (même non validées)
            $formData = $request->request->all();
            $formName = array_key_first($formData);
            if ($formName && isset($formData[$formName])) {
                $draftData = $formData[$formName];
                $session->set('inscription_brouillon', $draftData);
                
                // Sauvegarder aussi dans la BDD si l'utilisateur a déjà un enregistrement étudiant
                if ($this->getUser()) {
                    $etudiantBrouillon = $etudiantRepository->findOneBy(['utilisateur' => $this->getUser()]);
                    if ($etudiantBrouillon) {
                        $etudiantBrouillon->setDraftJson(json_encode($draftData));
                        $entityManager->flush();
                    }
                }
            }
            $this->addFlash('success', 'Votre inscription a été sauvegardée. Vous pouvez la reprendre plus tard.');
            return $this->redirectToRoute('app_home');
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Créer l'adresse
            $adresse = new Adresse();
            $adresse->setIdAdresse(uniqid('ADR_'));
            $adresse->setRue($form->get('adresse_rue')->getData());
            $adresse->setVille($form->get('adresse_commune')->getData() ?? '');
            $adresse->setCommune($form->get('adresse_commune')->getData());
            $adresse->setDepartement($form->get('adresse_departement')->getData());
            $entityManager->persist($adresse);
            
            // Créer le responsable légal 1
            $responsable1 = new ResponsableLegal();
            $responsable1->setNom($form->get('responsable1_nom')->getData());
            $responsable1->setPrenom($form->get('responsable1_prenom')->getData());
            $responsable1->setEmail($form->get('responsable1_email')->getData());
            $responsable1->setTel($form->get('responsable1_telMobile')->getData() ?? $form->get('responsable1_telDomicile')->getData());
            $responsable1->setProfession($form->get('responsable1_profession')->getData());
            $responsable1->setAdresse($adresse);
            $entityManager->persist($responsable1);
            
            // Créer le responsable légal 2 (optionnel)
            $responsable2 = null;
            if ($form->get('responsable2_nom')->getData()) {
                $responsable2 = new ResponsableLegal();
                $responsable2->setNom($form->get('responsable2_nom')->getData());
                $responsable2->setPrenom($form->get('responsable2_prenom')->getData());
                $responsable2->setEmail($form->get('responsable2_email')->getData());
                $responsable2->setTel($form->get('responsable2_telMobile')->getData());
                $responsable2->setProfession($form->get('responsable2_profession')->getData());
                $responsable2->setAdresse($adresse);
                $entityManager->persist($responsable2);
            }
            
            // Créer le transport
            $transport = new Transport();
            $transport->setIdTransport(uniqid('TRP_'));
            $transport->setVehicule($form->get('transport_ligne')->getData());
            $transport->setPDimmatriculation($form->get('transport_immatriculation')->getData());
            $entityManager->persist($transport);
            
            // Créer la scolarité (sans année pour éviter DateTime)
            $scolarite = new Scolarite();
            $scolarite->setIdScolar(uniqid('SCO_'));
            $scolarite->setClasse($form->get('classe')->getData());
            $scolarite->setLv1($form->get('lv1')->getData());
            $scolarite->setLv2($form->get('lv2')->getData());
            $entityManager->persist($scolarite);
            
            // Créer le dossier scolaire
            $dossSco = new DossSco();
            $dossSco->setRegimeSco($form->get('regimeSco')->getData());
            $dossSco->setSpecialite($form->get('specialite')->getData());
            $dossSco->setScolarite($scolarite);
            
            // Associer l'année scolaire en cours
            if ($anneeEnCours) {
                $dossSco->setAnneeSco($anneeEnCours);
            }
            
            $entityManager->persist($dossSco);
            
            // Créer le document étudiant
            $docEtudiant = new DocEtudiant();
            $docEtudiant->setNomAssSco($form->get('urgence_assuranceScolaire')->getData());
            $docEtudiant->setNSecuSocial($form->get('numeroSecuriteSociale')->getData());
            $docEtudiant->setLastVacc($form->get('urgence_dateVaccin')->getData());
            $docEtudiant->setNomDoc($form->get('urgence_medecinTraitant')->getData());
            
            // Gérer les uploads de fichiers
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Carte Vitale
            $carteVitaleFile = $form->get('doc_carteVitale')->getData();
            if ($carteVitaleFile) {
                $fileName = uniqid() . '.' . $carteVitaleFile->guessExtension();
                $carteVitaleFile->move($uploadDir, $fileName);
                $docEtudiant->setCarteVitale($fileName);
            }
            
            // Diplôme
            $diplomeFile = $form->get('doc_diplome')->getData();
            if ($diplomeFile) {
                $fileName = uniqid() . '.' . $diplomeFile->guessExtension();
                $diplomeFile->move($uploadDir, $fileName);
                $docEtudiant->setDiplome($fileName);
            }
            
            // Photo d'identité
            $photoFile = $form->get('doc_photoIdentite')->getData();
            if ($photoFile) {
                $fileName = uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move($uploadDir, $fileName);
                $docEtudiant->setPhotoIdentite($fileName);
            }
            
            // Certificat de scolarité
            $certificatFile = $form->get('doc_certificatScolarite')->getData();
            if ($certificatFile) {
                $fileName = uniqid() . '.' . $certificatFile->guessExtension();
                $certificatFile->move($uploadDir, $fileName);
                $docEtudiant->setCertificatScolarite($fileName);
            }
            
            $entityManager->persist($docEtudiant);
            
            // Associer toutes les entités à l'étudiant
            $etudiant->setAdresse($adresse);
            $etudiant->setResponsableLegal($responsable1);
            $etudiant->setTransport($transport);
            $etudiant->setDossSco($dossSco);
            $etudiant->setDocEtudiant($docEtudiant);
            
            // Associer l'utilisateur connecté à l'étudiant
            $utilisateur = $this->getUser();
            if ($utilisateur) {
                $etudiant->setUtilisateur($utilisateur);
                
                // Supprimer le brouillon de l'utilisateur s'il existe
                $etudiantBrouillon = $etudiantRepository->findOneBy(['utilisateur' => $utilisateur]);
                if ($etudiantBrouillon && $etudiantBrouillon !== $etudiant) {
                    $etudiantBrouillon->setDraftJson(null);
                }
            }
            
            // Sauvegarder l'étudiant
            $entityManager->persist($etudiant);
            $entityManager->flush();
            
            // Supprimer le brouillon après validation finale
            $session->remove('inscription_brouillon');
            
            // Envoyer l'email de confirmation
            try {
                $email = (new TemplatedEmail())
                    ->to($etudiant->getEmail())
                    ->subject('Confirmation de votre inscription - Lycée Fulbert')
                    ->htmlTemplate('email/confirmation.html.twig')
                    ->context([
                        'etudiant' => $etudiant,
                        'dossier' => $etudiant->getDossSco(),
                    ]);
                
                $mailer->send($email);
            } catch (\Exception $e) {
                // Log l'erreur mais continue le processus
                error_log('Erreur envoi email: ' . $e->getMessage());
            }
            
            // Message différent selon le rôle
            if ($this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('success', 'Inscription enregistrée avec succès !');
                return $this->redirectToRoute('app_admin_inscription_detail', ['id' => $etudiant->getIdEtudiant()]);
            } else {
                $this->addFlash('success', 'Votre inscription a été envoyée au lycée avec succès ! Vous recevrez une confirmation par email.');
                return $this->redirectToRoute('app_home');
            }
        }
        
        return $this->render('inscription/index.html.twig', [
            'form' => $form->createView(),
            'brouillon' => $brouillonData,
        ]);
    }
}

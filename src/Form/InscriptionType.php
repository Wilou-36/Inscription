<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Informations personnelles
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('numeroSecuriteSociale', TextType::class, [
                'label' => 'N° Sécurité Sociale',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input', 'placeholder' => '1 85 05 75 116 321 45'],
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date d\'entrée',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe',
                'required' => false,
                'choices' => [
                    'Masculin' => true,
                    'Féminin' => false,
                ],
                'expanded' => true,
                'placeholder' => false,
                'attr' => ['class' => 'fr-fieldset__element'],
            ])
            ->add('nationalite', TextType::class, [
                'label' => 'Nationalité',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('tel', TelType::class, [
                'label' => 'Téléphone Mobile Élève',
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel Élève',
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('diplome', ChoiceType::class, [
                'label' => 'Dernier diplôme obtenu',
                'required' => false,
                'choices' => [
                    'Baccalauréat général' => 'Baccalauréat général',
                    'Baccalauréat technologique' => 'Baccalauréat technologique',
                    'Baccalauréat professionnel' => 'Baccalauréat professionnel',
                    'BTS' => 'BTS',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'fr-select', 'data-toggle' => 'diplome-autre'],
            ])
            ->add('diplome_autre', TextType::class, [
                'label' => 'Précisez le diplôme',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input', 'data-target' => 'diplome-autre'],
            ])
            
            // Adresse
            ->add('adresse_rue', TextType::class, [
                'label' => 'Adresse',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('adresse_commune', TextType::class, [
                'label' => 'Commune',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('adresse_departement', TextType::class, [
                'label' => 'Département',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('adresse_codepostal', TextType::class, [
                'label' => 'Code Postal',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            
            // Informations scolaires
            ->add('redoublement', ChoiceType::class, [
                'label' => 'Redoublement BTS',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Oui' => 'oui',
                    'Non' => 'non',
                ],
                'expanded' => true,
                'label_attr' => ['class' => 'fr-label'],
                'placeholder' => false,
            ])
            ->add('regimeSco', ChoiceType::class, [
                'label' => 'Régime',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Externe' => 'Externe',
                    'Demi pensionnaire à l\'Unité (4€55)' => 'Demi-pensionnaire',
                ],
                'expanded' => true,
                'label_attr' => ['class' => 'fr-label'],
                'placeholder' => false,
            ])
            ->add('specialite', ChoiceType::class, [
                'label' => 'Spécialité BTS',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'BTS CG (Comptabilité et Gestion)' => 'BTS CG',
                    'BTS MCO (Management Commercial Opérationnel)' => 'BTS MCO',
                    'BTS NDRC (Négociation et Digitalisation de la Relation Client)' => 'BTS NDRC',
                    'BTS SIO (Services Informatiques aux Organisations)' => 'BTS SIO',
                ],
                'placeholder' => 'Sélectionnez une spécialité BTS',
                'attr' => ['class' => 'fr-select'],
            ])
            ->add('classe', ChoiceType::class, [
                'label' => 'Classe',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'BTS 1ère année' => 'BTS 1ère année',
                    'BTS 2ème année' => 'BTS 2ème année',
                ],
                'attr' => ['class' => 'fr-select'],
            ])
            ->add('lv1', ChoiceType::class, [
                'label' => 'Langue LV1',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Anglais' => 'Anglais',
                    'Espagnol' => 'Espagnol',
                    'Allemand' => 'Allemand',
                ],
                'attr' => ['class' => 'fr-select'],
            ])
            ->add('lv2', ChoiceType::class, [
                'label' => 'Langue LV2 (facultatif)',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Espagnol' => 'Espagnol',
                    'Allemand' => 'Allemand',
                    'Anglais' => 'Anglais',
                ],
                'placeholder' => 'Sélectionnez une langue (facultatif)',
                'attr' => ['class' => 'fr-select'],
            ])
            
            // Scolarité des 2 années antérieures
            ->add('scolarite_annee1', TextType::class, [
                'label' => 'Année scolaire 2023/2024',
                'mapped' => false,
                'required' => false,
                'data' => '2023/2024',
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('scolarite_classe1', TextType::class, [
                'label' => 'Classe',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('scolarite_etablissement1', TextType::class, [
                'label' => 'Établissement',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('scolarite_annee2', TextType::class, [
                'label' => 'Année scolaire 2024/2025',
                'mapped' => false,
                'required' => false,
                'data' => '2024/2025',
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('scolarite_classe2', TextType::class, [
                'label' => 'Classe',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('scolarite_etablissement2', TextType::class, [
                'label' => 'Établissement',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            
            // Responsables légaux
            ->add('responsable1_lien', ChoiceType::class, [
                'label' => 'Lien de parenté (Responsable 1)',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Père' => 'Pere',
                    'Mère' => 'Mere',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'fr-select'],
            ])
            ->add('responsable1_nom', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_prenom', TextType::class, [
                'label' => 'Prénom',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_adresse', TextType::class, [
                'label' => 'Adresse',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_commune', TextType::class, [
                'label' => 'Commune',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_email', EmailType::class, [
                'label' => 'Courriel',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_telDomicile', TelType::class, [
                'label' => 'Téléphone Domicile',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_telPro', TelType::class, [
                'label' => 'Téléphone Travail',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_telMobile', TelType::class, [
                'label' => 'Téléphone Mobile',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable1_sms', CheckboxType::class, [
                'label' => 'Acceptez-vous les SMS',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-checkbox'],
            ])
            ->add('responsable1_profession', TextType::class, [
                'label' => 'Profession',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            
            // Responsable 2
            ->add('responsable2_lien', ChoiceType::class, [
                'label' => 'Lien de parenté (Responsable 2)',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Père' => 'Pere',
                    'Mère' => 'Mere',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'fr-select'],
            ])
            ->add('responsable2_nom', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable2_prenom', TextType::class, [
                'label' => 'Prénom',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable2_email', EmailType::class, [
                'label' => 'Courriel',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable2_telMobile', TelType::class, [
                'label' => 'Téléphone Mobile',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('responsable2_profession', TextType::class, [
                'label' => 'Profession',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            
            // Si étudiant indépendant ou majeur
            ->add('etudiant_independant', CheckboxType::class, [
                'label' => 'L\'étudiant est indépendant ou majeur responsable',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-checkbox'],
            ])
            ->add('etudiant_adresse', TextType::class, [
                'label' => 'Adresse de l\'étudiant',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('etudiant_commune', TextType::class, [
                'label' => 'Commune',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('etudiant_telDomicile', TelType::class, [
                'label' => 'Téléphone Domicile',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('etudiant_telMobile2', TelType::class, [
                'label' => 'Téléphone Mobile de l\'élève',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            
            // Transport
            ->add('transport_ligne', ChoiceType::class, [
                'label' => 'Moyen de transport',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Filibus' => 'Filibus',
                    'SNCF' => 'SNCF',
                    'Véhicule personnel' => 'Personnel',
                    'Autres' => 'Autres',
                ],
                'expanded' => true,
                'label_attr' => ['class' => 'fr-label'],
                'placeholder' => false,
            ])
            ->add('transport_immatriculation', TextType::class, [
                'label' => 'N° d\'immatriculation du véhicule (si utilisation du parking)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            
            // Fiche d'urgence
            ->add('urgence_centreSecuSociale', TextType::class, [
                'label' => 'Nom et adresse du centre de sécurité sociale',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_assuranceScolaire', TextType::class, [
                'label' => 'Nom, adresse et n° de l\'assurance scolaire',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_telTravailPere', TelType::class, [
                'label' => 'N° de tel du travail du père',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_postePere', TextType::class, [
                'label' => 'Poste',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_telTravailMere', TelType::class, [
                'label' => 'N° de tel du travail de la mère',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_posteMere', TextType::class, [
                'label' => 'Poste',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_dateVaccin', DateType::class, [
                'label' => 'Date du dernier rappel de vaccin antitétanique (jj/mm/aaaa)',
                'widget' => 'single_text',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])
            ->add('urgence_observations', TextareaType::class, [
                'label' => 'Observations particulières (allergies, traitement en cours, précautions particulières)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input', 'rows' => 3],
            ])
            ->add('urgence_medecinTraitant', TextType::class, [
                'label' => 'Nom, adresse et téléphone du médecin traitant',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'fr-input'],
            ])

            // Documents à joindre
            ->add('doc_carteVitale', FileType::class, [
                'label' => 'Carte Vitale (PDF, JPG, PNG - max 5Mo)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF, JPG ou PNG valide',
                    ])
                ],
                'attr' => ['class' => 'fr-upload'],
            ])
            ->add('doc_diplome', FileType::class, [
                'label' => 'Dernier diplôme obtenu (PDF, JPG, PNG - max 5Mo)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF, JPG ou PNG valide',
                    ])
                ],
                'attr' => ['class' => 'fr-upload'],
            ])
            ->add('doc_photoIdentite', FileType::class, [
                'label' => 'Photo d\'identité (JPG, PNG - max 2Mo)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image JPG ou PNG valide',
                    ])
                ],
                'attr' => ['class' => 'fr-upload'],
            ])
            ->add('doc_certificatScolarite', FileType::class, [
                'label' => 'Certificat de scolarité (PDF, JPG, PNG - max 5Mo)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF, JPG ou PNG valide',
                    ])
                ],
                'attr' => ['class' => 'fr-upload'],
            ])
            
            // Acceptation RGPD
            ->add('acceptation_rgpd', CheckboxType::class, [
                'label' => 'J\'accepte les conditions générales d\'utilisation et la politique de confidentialité',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'fr-checkbox'],
            ])
            
            // Le bouton submit est déjà dans le template HTML
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}

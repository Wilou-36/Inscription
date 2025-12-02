
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE etudiant;
TRUNCATE TABLE utilisateur;
TRUNCATE TABLE adresse;
TRUNCATE TABLE transport;
TRUNCATE TABLE responsable_legal;
TRUNCATE TABLE doc_etudiant;
TRUNCATE TABLE doss_sco;
TRUNCATE TABLE scolarite;
TRUNCATE TABLE annee_sco;
SET FOREIGN_KEY_CHECKS = 1;


SET @PASSWORD_HASH = '$2y$13$QS1xYXQuYiUvLzExMTExMeJ.kBZJ8rZFwZF6EzKPGvKZqYqHRJCyy'; -- Hash pour 'password'
SET @ANNEE_SCO = '2025-09-01';


INSERT INTO annee_sco (annee, intitule, date_ouverture, date_fermeture) VALUES 
(@ANNEE_SCO, 'Année scolaire 2025-2026', '2025-06-01 00:00:00', '2025-09-01 23:59:59');

INSERT INTO scolarite (id_scolar, classe, lv1, lv2, etablissement, `option`) VALUES 
('BTS_SLAM', 'BTS SIO - Option SLAM (Développement)', 'Anglais', 'Espagnol', 'Lycée Fulbert', 'Développement'),
('BTS_SISR', 'BTS SIO - Option SISR (Réseaux)', 'Anglais', 'Allemand', 'Lycée Fulbert', 'Réseaux');


INSERT INTO utilisateur (id, identifiant, mot_de_passe, role) VALUES 
(100, 'admin@fulbert.fr', @PASSWORD_HASH, 'ROLE_ADMIN');

INSERT INTO utilisateur (id, identifiant, mot_de_passe, role) VALUES 
(101, 'sophie.dubois@test.com', @PASSWORD_HASH, 'ROLE_USER'), (102, 'marc.legrand@test.com', @PASSWORD_HASH, 'ROLE_USER'),
(103, 'lea.bertrand@test.com', @PASSWORD_HASH, 'ROLE_USER'), (104, 'david.moreau@test.com', @PASSWORD_HASH, 'ROLE_USER'),
(105, 'samir.elhassani@test.com', @PASSWORD_HASH, 'ROLE_USER'), -- MODIFIÉ
(106, 'fatou.diop@test.com', @PASSWORD_HASH, 'ROLE_USER'), (107, 'wei.zhang@test.com', @PASSWORD_HASH, 'ROLE_USER'),
(108, 'youssef.khan@test.com', @PASSWORD_HASH, 'ROLE_USER'), (109, 'malik.traore@test.com', @PASSWORD_HASH, 'ROLE_USER'),
(110, 'julie.petit@test.com', @PASSWORD_HASH, 'ROLE_USER'), (111, 'antoine.roy@test.com', @PASSWORD_HASH, 'ROLE_USER'),
(112, 'aisha.diallo@test.com', @PASSWORD_HASH, 'ROLE_USER'), (113, 'ming.chen@test.com', @PASSWORD_HASH, 'ROLE_USER'),
(114, 'samir.cherif@test.com', @PASSWORD_HASH, 'ROLE_USER'), (115, 'clara.duval@test.com', @PASSWORD_HASH, 'ROLE_USER');

INSERT INTO adresse (id_adresse, rue, ville, commune, departement) VALUES 
('ADR_1', '45 Rue des Lilas', 'CHARTRES', 'CHARTRES', '28'), ('ADR_2', '1 Rue de la Mairie', 'DREUX', 'DREUX', '28'),
('ADR_3', '36 Boulevard de l\'Europe', 'LUCÉ', 'LUCÉ', '28'), ('ADR_4', '12 Allée des Tilleuls', 'CHARTRES', 'CHARTRES', '28'),
('ADR_5', '25 Rue du Maghreb', 'MAINVILLIERS', 'MAINVILLIERS', '28'), -- ADR_5 pour EL HASSANI
('ADR_6', '8 Avenue de l\'Afrique', 'DREUX', 'DREUX', '28'),
('ADR_7', '15 Rue de Pékin', 'LUCÉ', 'LUCÉ', '28'), ('ADR_8', '60 Rue de l\'Emploi', 'CHARTRES', 'CHARTRES', '28'),
('ADR_9', '4 Avenue de la Renaissance', 'ÉPERNON', 'ÉPERNON', '28'), ('ADR_10', '50 Rue du Commerce', 'MAINVILLIERS', 'MAINVILLIERS', '28'),
('ADR_11', '2 Rue de la Gare', 'AUNEAU', 'AUNEAU', '28'), ('ADR_12', '10 Rue des Champs', 'COURVILLE-SUR-EURE', 'COURVILLE', '28'),
('ADR_13', '3 Rue du Château', 'NOGENT-LE-ROTROU', 'NOGENT', '28'), ('ADR_14', '7 Place de la Liberté', 'DREUX', 'DREUX', '28'),
('ADR_15', '18 Rue Saint-Pierre', 'CHARTRES', 'CHARTRES', '28');

INSERT INTO transport (id_transport, vehicule, p_dimmatriculation) VALUES 
('TRP_1', 'Voiture', 'EF-789-GH'), ('TRP_2', 'Deux Roues', 'M-345-BC'), ('TRP_3', 'Aucun', NULL), 
('TRP_4', 'Voiture', 'DG-987-AB'), ('TRP_5', 'Bus', NULL), -- TRP_5
('TRP_6', 'Voiture', 'ZZ-999-AA'), ('TRP_7', 'Vélo', NULL), ('TRP_8', 'Voiture', 'HH-111-II'), 
('TRP_9', 'Deux Roues', 'T-777-U'), ('TRP_10', 'Vélo', NULL), ('TRP_11', 'Train', NULL), 
('TRP_12', 'Voiture', 'CC-123-DD'), ('TRP_13', 'Voiture', 'LL-000-MM'), ('TRP_14', 'Bus', NULL), ('TRP_15', 'Voiture', 'EE-555-FF');


INSERT INTO doss_sco (id_sco, regime_sco, specialite, id_scolar, annee) VALUES 
(1, 'Initial', 'Mathématiques', 'BTS_SLAM', @ANNEE_SCO), (2, 'Alternance', 'Physique-Chimie', 'BTS_SISR', @ANNEE_SCO),
(3, 'Initial', 'Arts Plastiques', 'BTS_SLAM', @ANNEE_SCO), (4, 'Alternance', 'SVT', 'BTS_SISR', @ANNEE_SCO),
(5, 'Alternance', 'NSI', 'BTS_SLAM', @ANNEE_SCO), -- SCO_5 pour EL HASSANI (Alternance)
(6, 'Alternance', 'Maths', 'BTS_SISR', @ANNEE_SCO),
(7, 'Initial', 'Anglais', 'BTS_SLAM', @ANNEE_SCO), (8, 'Alternance', 'SES', 'BTS_SISR', @ANNEE_SCO),
(9, 'Initial', 'NSI', 'BTS_SLAM', @ANNEE_SCO), (10, 'Initial', 'HGGSP', 'BTS_SISR', @ANNEE_SCO),
(11, 'Alternance', 'SES', 'BTS_SLAM', @ANNEE_SCO), (12, 'Alternance', 'Physique', 'BTS_SISR', @ANNEE_SCO),
(13, 'Initial', 'Maths', 'BTS_SLAM', @ANNEE_SCO), (14, 'Alternance', 'SVT', 'BTS_SISR', @ANNEE_SCO),
(15, 'Initial', 'Sciences de l\'Ingénieur', 'BTS_SLAM', @ANNEE_SCO);


INSERT INTO doc_etudiant (id_docet, nom_ass_sco, carte_vitale, diplome, photo_identite, certificat_scolarite) VALUES 
(1, 'MAIF', 'cv_1.pdf', 'bac_1.pdf', 'photo_1.jpg', 'certif_1.pdf'), (2, 'AXA', 'cv_2.pdf', 'bac_2.pdf', 'photo_2.jpg', 'certif_2.pdf'),
(3, 'MACIF', 'cv_3.pdf', NULL, 'photo_3.jpg', 'certif_3.pdf'), (4, 'Crédit Mutuel', 'cv_4.pdf', 'bac_4.pdf', 'photo_4.jpg', 'certif_4.pdf'),
(5, 'GMF', 'cv_5.pdf', 'bac_5.pdf', 'photo_5.jpg', 'certif_5.pdf'), -- DOC_5
(6, 'ALLIANZ', 'cv_6.pdf', 'bac_6.pdf', 'photo_6.jpg', 'certif_6.pdf'),
(7, 'Groupama', 'cv_7.pdf', 'bac_7.pdf', NULL, 'certif_7.pdf'), (8, 'MAAF', 'cv_8.pdf', 'bac_8.pdf', 'photo_8.jpg', 'certif_8.pdf'),
(9, 'CIC', 'cv_9.pdf', 'bac_9.pdf', 'photo_9.jpg', 'certif_9.pdf'), (10, 'Caisse d\'Épargne', 'cv_10.pdf', 'bac_10.pdf', 'photo_10.jpg', 'certif_10.pdf'),
(11, 'MAIF', 'cv_11.pdf', 'bac_11.pdf', 'photo_11.jpg', 'certif_11.pdf'), (12, 'AXA', 'cv_12.pdf', NULL, 'photo_12.jpg', 'certif_12.pdf'),
(13, 'MACIF', 'cv_13.pdf', 'bac_13.pdf', 'photo_13.jpg', 'certif_13.pdf'), (14, 'GMF', 'cv_14.pdf', 'bac_14.pdf', 'photo_14.jpg', 'certif_14.pdf'),
(15, 'Groupama', 'cv_15.pdf', 'bac_15.pdf', 'photo_15.jpg', 'certif_15.pdf');

INSERT INTO responsable_legal (id_respleg, nom, prenom, profession, tel, cp, email, nom_emp, adresse_emp, id_adresse) VALUES 
(1, 'DUBOIS', 'Patrick', 'Développeur', '0601020304', '28000', 'patrick.dubois@resp.com', 'DUBOIS SARL', '10 Rue du Code', 'ADR_1'),
(2, 'LEGRAND', 'Sylvie', 'Infirmière', '0622334455', '28300', 'sylvie.legrand@resp.com', 'CHU Dreux', 'Centre Hospitalier', 'ADR_2'),
(3, 'BERTRAND', 'Denis', 'Professeur', '0633445566', '28000', 'denis.bertrand@resp.com', 'Lycée Zola', '22 Rue des Écoles', 'ADR_3'),
(4, 'MOREAU', 'Isabelle', 'Cadre Commercial', '0644556677', '28000', 'isabelle.moreau@resp.com', 'Auchan', 'Avenue du Commerce', 'ADR_4'),
(5, 'EL HASSANI', 'Fouad', 'Ingénieur', '0611223344', '28300', 'fouad.elhassani@resp.com', 'Renault', 'Zone Industrielle', 'ADR_5'), -- MODIFIÉ
(6, 'DIOP', 'Amadou', 'Consultant', '0622334455', '28300', 'amadou.diop@resp.com', 'Orange', 'Technopôle', 'ADR_6'),
(7, 'ZHANG', 'Mei', 'Chef de Cuisine', '0633445566', '28000', 'mei.zhang@resp.com', 'Restaurant du Soleil', 'Centre-ville', 'ADR_7'),
(8, 'KHAN', 'Samira', 'Pharmacienne', '0644556677', '28300', 'samira.khan@resp.com', 'Pharmacie Khan', 'Place de la Mairie', 'ADR_8'),
(9, 'TRAORÉ', 'Mariam', 'Assistante Sociale', '0655667788', '28230', 'mariam.traore@resp.com', 'Mairie', 'Service Social', 'ADR_9'),
(10, 'PETIT', 'Michel', 'Retraité', '0688776655', '28300', 'michel.petit@resp.com', 'N/A', 'N/A', 'ADR_10'),
(11, 'ROY', 'Sophie', 'Comptable', '0612345678', '28700', 'sophie.roy@resp.com', 'Cabinet Comptable', '3 Rue du Chiffre', 'ADR_11'),
(12, 'DIALLO', 'Fanta', 'Aide-Soignante', '0623456789', '28190', 'fanta.diallo@resp.com', 'EHPAD', 'Rue des Sages', 'ADR_12'),
(13, 'CHEN', 'Li', 'Informaticien', '0634567890', '28400', 'li.chen@resp.com', 'Infogérance', 'Centre d\'Affaires', 'ADR_13'),
(14, 'CHERIF', 'Farida', 'Commerciale', '0645678901', '28300', 'farida.cherif@resp.com', 'Concession Auto', 'Route de Paris', 'ADR_14'),
(15, 'DUVAL', 'Eric', 'Agriculteur', '0623456789', '28000', 'eric.duval@resp.com', 'Ferme Duval', 'Lieu-dit Les Vallées', 'ADR_15');


INSERT INTO etudiant (id_etudiant, nom, prenom, tel, email, date_naissance, sexe, diplome, statut, message_admin, id_adresse, id_transport, id_docet, id_sco, id_respleg, utilisateur_id) VALUES 
(1, 'DUBOIS', 'Sophie', '0710203040', 'sophie.dubois@test.com', '2005-09-15', FALSE, 'Bac S', 'valide', 'Dossier accepté. Bienvenue !', 'ADR_1', 'TRP_1', 1, 1, 1, 101),
(2, 'LEGRAND', 'Marc', '0750607080', 'marc.legrand@test.com', '2004-12-01', TRUE, 'Bac Pro SEN', 'refuse', 'Moyennes trop faibles pour l\'alternance.', 'ADR_2', 'TRP_2', 2, 2, 2, 102),
(3, 'BERTRAND', 'Léa', '0760708090', 'lea.bertrand@test.com', '2006-03-25', FALSE, 'Bac L', 'en_attente', 'Diplôme manquant.', 'ADR_3', 'TRP_3', 3, 3, 3, 103),
(4, 'MOREAU', 'David', '0770809000', 'david.moreau@test.com', '2005-01-10', TRUE, 'Bac STI2D', 'valide', 'Place en alternance confirmée.', 'ADR_4', 'TRP_4', 4, 4, 4, 104),
(5, 'EL HASSANI', 'Samir', '0711223344', 'samir.elhassani@test.com', '2005-03-01', TRUE, 'Bac S', 'valide', 'Dossier complet, bienvenu.', 'ADR_5', 'TRP_5', 5, 5, 5, 105), -- MODIFIÉ
(6, 'DIOP', 'Fatou', '0722334455', 'fatou.diop@test.com', '2004-11-20', FALSE, 'Bac Pro Commerce', 'refuse', 'Filière Pro non pertinente pour SIO.', 'ADR_6', 'TRP_6', 6, 6, 6, 106),
(7, 'ZHANG', 'Wei', '0733445566', 'wei.zhang@test.com', '2006-05-10', TRUE, 'Bac Général', 'en_attente', 'Photo d\'identité de mauvaise qualité.', 'ADR_7', 'TRP_7', 7, 7, 7, 107),
(8, 'KHAN', 'Youssef', '0744556677', 'youssef.khan@test.com', '2005-10-22', TRUE, 'Bac NSI', 'valide', 'Dossier de qualité. Accepté.', 'ADR_8', 'TRP_8', 8, 8, 8, 108),
(9, 'TRAORÉ', 'Malik', '0755667788', 'malik.traore@test.com', '2004-09-08', TRUE, 'Bac STI2D', 'en_attente', 'Manque l\'attestation d\'assurance scolaire.', 'ADR_9', 'TRP_9', 9, 9, 9, 109),
(10, 'PETIT', 'Julie', '0790010203', 'julie.petit@test.com', '2006-07-20', FALSE, 'Bac Général', 'refuse', 'Bulletins de notes incomplets.', 'ADR_10', 'TRP_10', 10, 10, 10, 110),
(11, 'ROY', 'Antoine', '0701020304', 'antoine.roy@test.com', '2005-02-14', TRUE, 'Bac ES', 'valide', 'Dossier complet et pertinent.', 'ADR_11', 'TRP_11', 11, 11, 11, 111),
(12, 'DIALLO', 'Aïsha', '0723456789', 'aisha.diallo@test.com', '2005-04-10', FALSE, 'Bac STMG', 'valide', 'Dossier validé pour l\'alternance.', 'ADR_12', 'TRP_12', 12, 12, 12, 112),
(13, 'CHEN', 'Ming', '0734567890', 'ming.chen@test.com', '2006-01-28', TRUE, 'Bac NSI', 'en_attente', 'Vérification en cours de la certification LV2.', 'ADR_13', 'TRP_13', 13, 13, 13, 113),
(14, 'CHERIF', 'Samir', '0745678901', 'samir.cherif@test.com', '2004-10-05', TRUE, 'Bac Pro MELEC', 'refuse', 'Profil non adapté à la filière SISR demandée.', 'ADR_14', 'TRP_14', 14, 14, 14, 114),
(15, 'DUVAL', 'Clara', '0734567890', 'clara.duval@test.com', '2006-04-20', FALSE, 'Bac S', 'en_attente', 'Dossier en attente de la dernière vérification académique.', 'ADR_15', 'TRP_15', 15, 15, 15, 115);
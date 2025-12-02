CREATE DATABASE IF NOT EXISTS inscription_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE inscription_db;

CREATE TABLE IF NOT EXISTS utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(180) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'ROLE_USER',
    INDEX idx_identifiant (identifiant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin (
    identifiant VARCHAR(50) PRIMARY KEY,
    mot_de_passe VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS adresse (
    id_adresse VARCHAR(50) PRIMARY KEY,
    rue VARCHAR(100) NOT NULL,
    ville VARCHAR(50) NOT NULL,
    commune VARCHAR(50) NOT NULL,
    departement VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS transport (
    id_transport VARCHAR(50) PRIMARY KEY,
    vehicule VARCHAR(50),
    p_dimmatriculation VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS annee_sco (
    annee DATE PRIMARY KEY,
    intitule VARCHAR(100),
    date_ouverture DATETIME,
    date_fermeture DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS scolarite (
    id_scolar VARCHAR(50) PRIMARY KEY,
    classe VARCHAR(50),
    lv1 VARCHAR(50),
    lv2 VARCHAR(50),
    etablissement VARCHAR(100),
    `option` VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS doss_sco (
    id_sco INT AUTO_INCREMENT PRIMARY KEY,
    regime_sco VARCHAR(50),
    specialite VARCHAR(50),
    id_scolar VARCHAR(50) NOT NULL,
    annee DATE,
    FOREIGN KEY (id_scolar) REFERENCES scolarite(id_scolar) ON DELETE CASCADE,
    FOREIGN KEY (annee) REFERENCES annee_sco(annee) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS doc_etudiant (
    id_docet INT AUTO_INCREMENT PRIMARY KEY,
    nom_ass_sco VARCHAR(100),
    n_ass_sco VARCHAR(50),
    adresse_ass_sco VARCHAR(150),
    last_vacc DATE,
    nom_doc VARCHAR(100),
    adresse_doc VARCHAR(150),
    tel_doc VARCHAR(20),
    n_secu_social VARCHAR(20),
    carte_vitale VARCHAR(255),
    diplome VARCHAR(255),
    photo_identite VARCHAR(255),
    certificat_scolarite VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS responsable_legal (
    id_respleg INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    profession VARCHAR(100),
    tel VARCHAR(20),
    cp VARCHAR(10),
    email VARCHAR(100),
    nom_emp VARCHAR(100),
    adresse_emp VARCHAR(150),
    id_adresse VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_adresse) REFERENCES adresse(id_adresse) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS etudiant (
    id_etudiant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    tel VARCHAR(20),
    email VARCHAR(100),
    date_naissance DATE,
    sexe BOOLEAN,
    adresse_medicale VARCHAR(150),
    diplome VARCHAR(100),
    draft_json TEXT,
    statut VARCHAR(20) DEFAULT 'en_attente',
    message_admin TEXT,
    id_adresse VARCHAR(50) NOT NULL,
    id_transport VARCHAR(50) NOT NULL,
    id_docet INT NOT NULL,
    id_sco INT NOT NULL,
    id_respleg INT NOT NULL,
    utilisateur_id INT NOT NULL,
    FOREIGN KEY (id_adresse) REFERENCES adresse(id_adresse) ON DELETE CASCADE,
    FOREIGN KEY (id_transport) REFERENCES transport(id_transport) ON DELETE CASCADE,
    FOREIGN KEY (id_docet) REFERENCES doc_etudiant(id_docet) ON DELETE CASCADE,
    FOREIGN KEY (id_sco) REFERENCES doss_sco(id_sco) ON DELETE CASCADE,
    FOREIGN KEY (id_respleg) REFERENCES responsable_legal(id_respleg) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    INDEX idx_statut (statut),
    INDEX idx_utilisateur (utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS doctrine_migration_versions (
    version VARCHAR(191) PRIMARY KEY,
    executed_at DATETIME DEFAULT NULL,
    execution_time INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admin (identifiant, mot_de_passe) VALUES 
('admin', '$2y$13$QS1xYXQuYiUvLzExMTExMeJ.kBZJ8rZFwZF6EzKPGvKZqYqHRJCyy');

INSERT INTO annee_sco (annee, intitule, date_ouverture, date_fermeture) VALUES 
('2025-09-01', 'Année scolaire 2025-2026', '2025-06-01 00:00:00', '2025-09-01 23:59:59');

INSERT INTO utilisateur (identifiant, mot_de_passe, role) VALUES 
('user@test.com', '$2y$13$QS1xYXQuYiUvLzExMTExMeJ.kBZJ8rZFwZF6EzKPGvKZqYqHRJCyy', 'ROLE_USER');

CREATE OR REPLACE VIEW v_stats_inscriptions AS
SELECT 
    statut,
    COUNT(*) as nombre,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM etudiant), 2) as pourcentage
FROM etudiant
GROUP BY statut;

CREATE OR REPLACE VIEW v_etudiants_complet AS
SELECT 
    e.id_etudiant,
    e.nom,
    e.prenom,
    e.email,
    e.tel,
    e.date_naissance,
    e.statut,
    a.ville,
    a.departement,
    rl.nom as responsable_nom,
    rl.prenom as responsable_prenom,
    rl.email as responsable_email,
    ds.regime_sco,
    ds.specialite,
    s.classe,
    s.etablissement
FROM etudiant e
LEFT JOIN adresse a ON e.id_adresse = a.id_adresse
LEFT JOIN responsable_legal rl ON e.id_respleg = rl.id_respleg
LEFT JOIN doss_sco ds ON e.id_sco = ds.id_sco
LEFT JOIN scolarite s ON ds.id_scolar = s.id_scolar;

DELIMITER //

CREATE PROCEDURE sp_count_by_status(IN p_statut VARCHAR(20))
BEGIN
    SELECT COUNT(*) as total
    FROM etudiant
    WHERE statut = p_statut;
END //

CREATE PROCEDURE sp_update_statut(
    IN p_id_etudiant INT,
    IN p_statut VARCHAR(20),
    IN p_message_admin TEXT
)
BEGIN
    UPDATE etudiant
    SET statut = p_statut,
        message_admin = p_message_admin
    WHERE id_etudiant = p_id_etudiant;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER tr_validate_email_before_insert
BEFORE INSERT ON etudiant
FOR EACH ROW
BEGIN
    IF NEW.email IS NOT NULL AND NEW.email NOT LIKE '%@%.%' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Format d\'email invalide';
    END IF;
END //

CREATE TABLE IF NOT EXISTS log_statut_changes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT NOT NULL,
    ancien_statut VARCHAR(20),
    nouveau_statut VARCHAR(20),
    date_changement TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci //

CREATE TRIGGER tr_log_statut_change
AFTER UPDATE ON etudiant
FOR EACH ROW
BEGIN
    IF OLD.statut != NEW.statut THEN
        INSERT INTO log_statut_changes (id_etudiant, ancien_statut, nouveau_statut)
        VALUES (NEW.id_etudiant, OLD.statut, NEW.statut);
    END IF;
END //

DELIMITER ;

CREATE INDEX idx_etudiant_nom_prenom ON etudiant(nom, prenom);
CREATE INDEX idx_etudiant_email ON etudiant(email);
CREATE INDEX idx_responsable_email ON responsable_legal(email);
CREATE INDEX idx_doss_sco_annee ON doss_sco(annee);

SHOW TABLES;

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125131711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scolarite DROP FOREIGN KEY scolarite_ibfk_1');
        $this->addSql('CREATE TABLE annee_sco (annee DATE NOT NULL, PRIMARY KEY(annee)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE anne_sco');
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY admin_ibfk_1');
        $this->addSql('ALTER TABLE `admin` CHANGE mot_de_passe mot_de_passe VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE adresse CHANGE rue rue VARCHAR(100) NOT NULL, CHANGE ville ville VARCHAR(50) NOT NULL, CHANGE commune commune VARCHAR(50) NOT NULL, CHANGE departement departement VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE doc_etudiant CHANGE id_docet id_docet INT AUTO_INCREMENT NOT NULL, CHANGE nom_ass_sco nom_ass_sco VARCHAR(100) DEFAULT NULL, CHANGE n_ass_sco n_ass_sco VARCHAR(50) DEFAULT NULL, CHANGE adresse_ass_sco adresse_ass_sco VARCHAR(150) DEFAULT NULL, CHANGE last_vacc last_vacc DATE DEFAULT NULL, CHANGE nom_doc nom_doc VARCHAR(100) DEFAULT NULL, CHANGE adresse_doc adresse_doc VARCHAR(150) DEFAULT NULL, CHANGE tel_doc tel_doc VARCHAR(20) DEFAULT NULL, CHANGE n_secu_social n_secu_social VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE doss_sco CHANGE id_sco id_sco INT AUTO_INCREMENT NOT NULL, CHANGE regime_sco regime_sco VARCHAR(50) DEFAULT NULL, CHANGE specialite specialite VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE doss_sco RENAME INDEX id_scolar TO IDX_29BD250424B4F95B');
        $this->addSql('ALTER TABLE etudiant DROP INDEX identifiant, ADD INDEX IDX_717E22E3C90409EC (identifiant)');
        $this->addSql('ALTER TABLE etudiant ADD draft_json LONGTEXT DEFAULT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE tel tel VARCHAR(20) DEFAULT NULL, CHANGE email email VARCHAR(100) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE adresse_medicale adresse_medicale VARCHAR(150) DEFAULT NULL, CHANGE diplome diplome VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX id_adresse TO IDX_717E22E31DC2A166');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX id_transport TO IDX_717E22E3E69E9D09');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX id_docet TO IDX_717E22E32223DF4B');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX id_sco TO IDX_717E22E3A48FA3EC');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX id_respleg TO IDX_717E22E33FE34A3B');
        $this->addSql('ALTER TABLE responsable_legal CHANGE id_respleg id_respleg INT AUTO_INCREMENT NOT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE profession profession VARCHAR(100) DEFAULT NULL, CHANGE tel tel VARCHAR(20) DEFAULT NULL, CHANGE cp cp VARCHAR(10) DEFAULT NULL, CHANGE email email VARCHAR(100) DEFAULT NULL, CHANGE nom_emp nom_emp VARCHAR(100) DEFAULT NULL, CHANGE adresse_emp adresse_emp VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE responsable_legal RENAME INDEX id_adresse TO IDX_82F3E5321DC2A166');
        $this->addSql('ALTER TABLE scolarite DROP filiere, CHANGE classe classe VARCHAR(50) DEFAULT NULL, CHANGE lv1 lv1 VARCHAR(50) DEFAULT NULL, CHANGE lv2 lv2 VARCHAR(50) DEFAULT NULL, CHANGE etablissement etablissement VARCHAR(100) DEFAULT NULL, CHANGE `option` `option` VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT FK_276250ABDE92C5CF FOREIGN KEY (annee) REFERENCES annee_sco (annee)');
        $this->addSql('ALTER TABLE scolarite RENAME INDEX annee TO IDX_276250ABDE92C5CF');
        $this->addSql('ALTER TABLE transport CHANGE vehicule vehicule VARCHAR(50) DEFAULT NULL, CHANGE p_dimmatriculation p_dimmatriculation VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE mot_de_passe mot_de_passe VARCHAR(100) NOT NULL, CHANGE role role VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scolarite DROP FOREIGN KEY FK_276250ABDE92C5CF');
        $this->addSql('CREATE TABLE anne_sco (annee DATE NOT NULL, PRIMARY KEY(annee)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE annee_sco');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE doss_sco CHANGE id_sco id_sco INT NOT NULL, CHANGE regime_sco regime_sco VARCHAR(50) DEFAULT \'NULL\', CHANGE specialite specialite VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE doss_sco RENAME INDEX idx_29bd250424b4f95b TO id_scolar');
        $this->addSql('ALTER TABLE doc_etudiant CHANGE id_docet id_docet INT NOT NULL, CHANGE nom_ass_sco nom_ass_sco VARCHAR(100) DEFAULT \'NULL\', CHANGE n_ass_sco n_ass_sco VARCHAR(50) DEFAULT \'NULL\', CHANGE adresse_ass_sco adresse_ass_sco VARCHAR(150) DEFAULT \'NULL\', CHANGE last_vacc last_vacc DATE DEFAULT \'NULL\', CHANGE nom_doc nom_doc VARCHAR(100) DEFAULT \'NULL\', CHANGE adresse_doc adresse_doc VARCHAR(150) DEFAULT \'NULL\', CHANGE tel_doc tel_doc VARCHAR(20) DEFAULT \'NULL\', CHANGE n_secu_social n_secu_social VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE scolarite ADD filiere VARCHAR(100) DEFAULT \'NULL\', CHANGE classe classe VARCHAR(50) DEFAULT \'NULL\', CHANGE lv1 lv1 VARCHAR(50) DEFAULT \'NULL\', CHANGE lv2 lv2 VARCHAR(50) DEFAULT \'NULL\', CHANGE etablissement etablissement VARCHAR(100) DEFAULT \'NULL\', CHANGE `option` `option` VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT scolarite_ibfk_1 FOREIGN KEY (annee) REFERENCES anne_sco (annee)');
        $this->addSql('ALTER TABLE scolarite RENAME INDEX idx_276250abde92c5cf TO annee');
        $this->addSql('ALTER TABLE etudiant DROP INDEX IDX_717E22E3C90409EC, ADD UNIQUE INDEX identifiant (identifiant)');
        $this->addSql('ALTER TABLE etudiant DROP draft_json, CHANGE nom nom VARCHAR(100) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(100) DEFAULT \'NULL\', CHANGE tel tel VARCHAR(20) DEFAULT \'NULL\', CHANGE email email VARCHAR(100) DEFAULT \'NULL\', CHANGE date_naissance date_naissance DATE DEFAULT \'NULL\', CHANGE adresse_medicale adresse_medicale VARCHAR(150) DEFAULT \'NULL\', CHANGE diplome diplome VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX idx_717e22e3a48fa3ec TO id_sco');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX idx_717e22e31dc2a166 TO id_adresse');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX idx_717e22e33fe34a3b TO id_respleg');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX idx_717e22e3e69e9d09 TO id_transport');
        $this->addSql('ALTER TABLE etudiant RENAME INDEX idx_717e22e32223df4b TO id_docet');
        $this->addSql('ALTER TABLE adresse CHANGE rue rue VARCHAR(100) DEFAULT \'NULL\', CHANGE ville ville VARCHAR(50) DEFAULT \'NULL\', CHANGE commune commune VARCHAR(50) DEFAULT \'NULL\', CHANGE departement departement VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE mot_de_passe mot_de_passe VARCHAR(100) DEFAULT \'NULL\', CHANGE role role VARCHAR(20) DEFAULT \'\'\'ROLE_USER\'\'\'');
        $this->addSql('ALTER TABLE `admin` CHANGE mot_de_passe mot_de_passe VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT admin_ibfk_1 FOREIGN KEY (identifiant) REFERENCES utilisateur (identifiant)');
        $this->addSql('ALTER TABLE responsable_legal CHANGE id_respleg id_respleg INT NOT NULL, CHANGE nom nom VARCHAR(100) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(100) DEFAULT \'NULL\', CHANGE profession profession VARCHAR(100) DEFAULT \'NULL\', CHANGE tel tel VARCHAR(20) DEFAULT \'NULL\', CHANGE cp cp VARCHAR(10) DEFAULT \'NULL\', CHANGE email email VARCHAR(100) DEFAULT \'NULL\', CHANGE nom_emp nom_emp VARCHAR(100) DEFAULT \'NULL\', CHANGE adresse_emp adresse_emp VARCHAR(150) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE responsable_legal RENAME INDEX idx_82f3e5321dc2a166 TO id_adresse');
        $this->addSql('ALTER TABLE transport CHANGE vehicule vehicule VARCHAR(50) DEFAULT \'NULL\', CHANGE p_dimmatriculation p_dimmatriculation VARCHAR(20) DEFAULT \'NULL\'');
    }
}

-- Création de la base de données

CREATE DATABASE if NOT EXISTS `touche_pas_au_klaxon` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `touche_pas_au_klaxon`;

-- Création table AGENCE

CREATE TABLE if NOT EXISTS `agence` (
    `id_agence` INT AUTO_INCREMENT,
    `nom_ville` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id_agence`)
) ENGINE=InnoDB;

-- Création table UTILISATEUR

CREATE TABLE if NOT EXISTS `utilisateur` (
    `id_utilisateur` INT AUTO_INCREMENT,
    `nom` VARCHAR(50) NOT NULL,
    `prenom` VARCHAR(50) NOT NULL,
    `telephone` VARCHAR(15) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `est_admin` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB;

-- Création de la table TRAJET

CREATE TABLE if NOT EXISTS `trajet` (
    `id_trajet` INT AUTO_INCREMENT,
    `date_heure_depart` DATETIME NOT NULL,
    `date_heure_arrivee` DATETIME NOT NULL,
    `places_totales` INT NOT NULL,
    `places_disponibles` INT NOT NULL,
    `id_utilisateur_auteur` INT NOT NULL,
    `id_agence_depart` INT NOT NULL,
    `id_agence_arrivee` INT NOT NULL,
    PRIMARY KEY (`id_trajet`),
    CONSTRAINT `fk_trajet_utilisateur` FOREIGN KEY (`id_utilisateur_auteur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
    CONSTRAINT `fk_trajet_agence_depart` FOREIGN KEY (`id_agence_depart`) REFERENCES `agence` (`id_agence`) ON DELETE RESTRICT,
    CONSTRAINT `fk_trajet_agence_arrivee` FOREIGN KEY (`id_agence_arrivee`) REFERENCES `agence` (`id_agence`) ON DELETE RESTRICT
) ENGINE=InnoDB;
-- Script pour ajouter la colonne STATUS à la table employee
-- À exécuter sur la base de données existante

-- Ajouter la colonne STATUS
ALTER TABLE `employee` ADD COLUMN `STATUS` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE' AFTER `LOCATION_ID`;

-- Mettre à jour tous les employés existants comme ACTIVE
UPDATE `employee` SET `STATUS` = 'ACTIVE' WHERE `STATUS` IS NULL;

-- Vérifier que la colonne a été ajoutée
DESCRIBE `employee`; 
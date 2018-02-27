DROP PROCEDURE IF EXISTS create_adp5;
DELIMITER $$
CREATE PROCEDURE create_adp5()
adp5:BEGIN
SET FOREIGN_KEY_CHECKS=0;

-- Utilisation
/*
CALL create_adp5();
*/

/*------------------------------------
Création table com_completude
complétude manuelle
Issue de bve_completude et bve_completude_alert
-------------------------------------*/
DROP TABLE IF EXISTS com_completude;
CREATE TABLE `com_completude` (
  `com_id_completude` INT NOT NULL AUTO_INCREMENT,
  `com_id_user` VARCHAR(20) NOT NULL,
  `com_libelle` VARCHAR(100) NOT NULL,
  `com_privee` BOOL NOT NULL DEFAULT 0,
  `com_auto` BOOL NOT NULL DEFAULT 0,
  `com_email` TEXT NULL,
  `com_periode` VARCHAR(12) NOT NULL DEFAULT 'quotidien',
  `com_avec_documents` BOOL NOT NULL DEFAULT 1,
  `com_population` VARCHAR(7) NOT NULL DEFAULT 'Tous',
  `com_notification` VARCHAR(9) NOT NULL DEFAULT 'EnAttente',
  `com_created_at` DATETIME,
  `com_updated_at` DATETIME,
  PRIMARY KEY (`com_id_completude`),
  UNIQUE KEY `com_usr_lib` (`com_id_user`, `com_libelle`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Permet de contrôler la présence des documents associés à 1 matricule';
/*--------------------------------------*/


/*------------------------------------
Création table cty_completude_type
Complétude manuelle
Issue de bve_completude_type
-------------------------------------*/
DROP TABLE IF EXISTS cty_completude_type;
CREATE TABLE `cty_completude_type` (
  `cty_id` INT NOT NULL AUTO_INCREMENT,
--  `cty_id_completude` int NOT NULL,
  `cty_id_completude` INT NULL,
--  `cty_type` varchar(10) NOT NULL, -- clé étrangère vers bve_type
  `cty_type` VARCHAR(10) NULL, -- clé étrangère vers bve_type
  `cty_created_at` DATETIME,
  `cty_updated_at` DATETIME,
  PRIMARY KEY (`cty_id`),
  KEY (`cty_id_completude`,`cty_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Liste des tiroirs qui composent une complétude';
/*--------------------------------------*/


/*------------------------------------
Création table con_confi
Issue de bve_config + vdm_cfg
-------------------------------------*/
DROP TABLE IF EXISTS con_config;
CREATE TABLE `con_config` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_variable` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `con_valeur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `con_label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `con_created_at` datetime DEFAULT NULL,
  `con_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`con_id`),
  UNIQUE KEY `con_variable` (`con_variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='"Variables de configuration spécifique au client (type Abonnement, Code pac, options)';
/*--------------------------------------*/


/*------------------------------------
Création table cdv_cycle_de_vie
Issue de bve_cycle_de_vie
-------------------------------------*/
DROP TABLE IF EXISTS cdv_cycle_de_vie;
CREATE TABLE `cdv_cycle_de_vie` (
  `cdv_id` INT NOT NULL AUTO_INCREMENT,
  `cdv_yyss` VARCHAR(4) NULL,
  `cdv_nb_doc_indiv` SMALLINT DEFAULT NULL,
  `cdv_nb_doc_collect` SMALLINT DEFAULT NULL,
  `cdv_created_at` DATETIME,
  `cdv_updated_at` DATETIME,
  PRIMARY KEY (`cdv_id`),
  UNIQUE KEY `cdv_yyss` (`cdv_yyss`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table err_erreur
REMARQUE : à conserver ?
Issue de bve_erreur
-------------------------------------*/
DROP TABLE IF EXISTS err_erreur;
CREATE TABLE `err_erreur` (
  `err_id` INT NOT NULL AUTO_INCREMENT,
  `err_code` SMALLINT NOT NULL,
  `err_message` VARCHAR(100) NOT NULL,
  `err_server` TEXT NOT NULL,
  `err_created_at` DATETIME,
  `err_updated_at` DATETIME,
  PRIMARY KEY (`err_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Log les erreurs d''accès';
/*--------------------------------------*/


/*------------------------------------
Création table fol_folder
Issue de bve_folder
-------------------------------------*/
DROP TABLE IF EXISTS fol_folder;
CREATE TABLE `fol_folder` (
  `fol_id` INT NOT NULL AUTO_INCREMENT,
  `fol_libelle` VARCHAR(100) NOT NULL,
  -- Alexandre, le 11 mars 2016 : Ajout de la notion de propriétaire sur le dossier et pas sur la relation dossier <-> user
  `fol_id_owner` VARCHAR(50) NOT NULL,
  -- Alexandre, le 19 avril 2016 : Ajout de type qui détermine si c'est un dossier ou panier
  `fol_type` VARCHAR(3) NOT NULL DEFAULT 'FOL',
  -- Alexandre, le 25 mars 2016 : Ajout de nb_doc qui était dans fus_folder_user
  `fol_nb_doc` INT NOT NULL DEFAULT 0,
  `fol_created_at` DATETIME,
  `fol_updated_at` DATETIME,
  PRIMARY KEY (`fol_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste les dossiers et paniers';
/*--------------------------------------*/


/*------------------------------------
Création table fdo_folder_doc
Issue de bve_folder_doc
-------------------------------------*/
DROP TABLE IF EXISTS fdo_folder_doc;
CREATE TABLE `fdo_folder_doc` (
  `fdo_id` INT NOT NULL AUTO_INCREMENT,
  `fdo_id_doc` INT NOT NULL,
  -- Alexandre, le 11 mars 2015 : Est une clé étrangère vers fol_folder
  `fdo_id_folder` INT NOT NULL,
  `fdo_created_at` DATETIME,
  `fdo_updated_at` DATETIME,
  PRIMARY KEY (`fdo_id`),
  UNIQUE KEY `fdo_id_fol_doc` (`fdo_id_folder`, `fdo_id_doc`),
  KEY `fdo_id_doc` (`fdo_id_doc`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste les documents des dossiers et paniers';
/*--------------------------------------*/


/*------------------------------------
Création table fus_folder_user
Issue de bve_folder_user
-------------------------------------*/
DROP TABLE IF EXISTS fus_folder_user;
CREATE TABLE `fus_folder_user` (
-- Alexandre, le 01/02/2015 : Ajout d'une clé primaire, retrait de la clé composée
  `fus_id` INT NOT NULL AUTO_INCREMENT,
--  `fus_id_folder` int NOT NULL,
  `fus_id_folder` INT NULL,
--  `fus_id_user` varchar(50) NOT NULL, -- id_user mais pointe sur usr_login
  `fus_id_user` VARCHAR(50) NULL, -- id_user mais pointe sur usr_login
  `fus_date_acces` DATETIME,
  `fus_created_at` DATETIME,
  `fus_updated_at` DATETIME,
  PRIMARY KEY (`fus_id`),
  UNIQUE KEY `fus_id_fol_usr` (`fus_id_folder`, `fus_id_user`),
  KEY `fus_id_user` (`fus_id_user`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Relation entre un dossier et un utilisateur';
/*--------------------------------------*/


/*------------------------------------
Création table pro_profil
Issue de bve_profil
-------------------------------------*/
DROP TABLE IF EXISTS pro_profil;
CREATE TABLE `pro_profil` (
  `pro_id` INT NOT NULL AUTO_INCREMENT,
  `pro_libelle` VARCHAR(100) NOT NULL,
  `pro_order` BOOL NOT NULL,
  `pro_import` BOOL NOT NULL,
  `pro_generic` BOOL DEFAULT FALSE,
  `pro_adp` BOOL DEFAULT FALSE,
  `pro_arc` BOOL DEFAULT FALSE,
  `fdo_created_at` DATETIME,
  `fdo_updated_at` DATETIME,
  PRIMARY KEY (`pro_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT="Définition du périmètre utilisateur";
/*--------------------------------------*/


/*------------------------------------
Création table pus_profil_user
Issue de bve_profil_user
-------------------------------------*/
DROP TABLE IF EXISTS pus_profil_user;
CREATE TABLE `pus_profil_user` (
  `pus_id` INT NOT NULL AUTO_INCREMENT,
  `pus_id_profil` INT NOT NULL,
  `pus_id_user` VARCHAR(50) NOT NULL,
  `pus_created_at` DATETIME,
  `pus_updated_at` DATETIME,
  PRIMARY KEY (`pus_id`),
  UNIQUE KEY `pus_id_pro_usr` (`pus_id_profil`, `pus_id_user`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Relation entre un profil et un utilisateur';
/*--------------------------------------*/


/*------------------------------------
Création table pde_profil_def
Issue de bve_profil_def
-------------------------------------*/
DROP TABLE IF EXISTS pde_profil_def;
CREATE TABLE `pde_profil_def` (
  `pde_id` INT NOT NULL,
  `pde_id_profil_def` INT NOT NULL,
  `pde_type` VARCHAR(10) NOT NULL,
  `pde_created_at` DATETIME,
  `pde_updated_at` DATETIME,
  PRIMARY KEY (`pde_id`,`pde_id_profil_def`,`pde_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Définition du périmètre et de la population 1 ligne = 1 habilitation, pas de ligne = pas de droit';
/*--------------------------------------*/


/*------------------------------------
Création table pda_profil_def_appli
Issue de bve_profil_def_appli
-------------------------------------*/
DROP TABLE IF EXISTS pda_profil_def_appli;
CREATE TABLE `pda_profil_def_appli` (
  `pda_id` INT NOT NULL AUTO_INCREMENT,
  `pda_libelle` VARCHAR(100) NOT NULL,
  `pda_ref_bve` TEXT NOT NULL,
  `pda_nbi` INT NOT NULL,
  `pda_nbc` INT NOT NULL,
  `pda_adp` BOOL NOT NULL DEFAULT FALSE,
  `pda_created_at` DATETIME,
  `pda_updated_at` DATETIME,
  PRIMARY KEY (`pda_id`),
  UNIQUE KEY `pda_libelle` (`pda_libelle`)
--  KEY `adp` (`pda_adp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Définition du périmètre applicatif';
/*--------------------------------------*/


/*------------------------------------
Création table pdh_profil_def_habi
Issue de bve_profil_def_habi
-------------------------------------*/
DROP TABLE IF EXISTS pdh_profil_def_habi;
CREATE TABLE `pdh_profil_def_habi` (
  `pdh_id` INT NOT NULL AUTO_INCREMENT,
  `pdh_libelle` VARCHAR(100) NOT NULL,
  `pdh_habilitation_i` LONGTEXT NOT NULL,
  `pdh_habilitation_c` LONGTEXT NOT NULL,
  `pdh_mode` VARCHAR(20) NOT NULL DEFAULT 'REFERENCE',
  `pdh_adp` BOOL NOT NULL DEFAULT FALSE,
  `pdh_created_at` DATETIME,
  `pdh_updated_at` DATETIME,
  PRIMARY KEY (`pdh_id`),
  UNIQUE KEY `pdh_libelle` (`pdh_libelle`)
--  KEY `adp` (`pda_adp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Définition du périmètre sur population';
/*--------------------------------------*/


/*------------------------------------
Création table typ_type
Issue de bve_type
-------------------------------------*/
DROP TABLE IF EXISTS typ_type;
CREATE TABLE `typ_type` (
  `typ_code` VARCHAR(10) NOT NULL,
  `typ_code_adp` VARCHAR(10) NOT NULL,
  `typ_id_niveau` CHAR(5) NOT NULL,
  `typ_type` SMALLINT NOT NULL DEFAULT 1,
  `typ_individuel` BOOL NOT NULL DEFAULT 1,
  `typ_vie_duree` SMALLINT NOT NULL DEFAULT 30,
  `typ_num_ordre_1` SMALLINT NOT NULL,
  `typ_num_ordre_2` SMALLINT NOT NULL,
  `typ_num_ordre_3` SMALLINT NOT NULL,
  `typ_num_ordre_4` SMALLINT NOT NULL,
  `typ_date_effet` VARCHAR(40) NOT NULL,
  `typ_created_at` DATETIME,
  `typ_updated_at` DATETIME,
-- La clef primaire est typ_code et non typ_id avec AUTO INCREMENT
  PRIMARY KEY (`typ_code`),
  KEY `typ_type` (`typ_type`),
  KEY `typ_id_niveau` (`typ_id_niveau`),
-- Alexandre, le 05/04/2016
  KEY `typ_num_ordre` (`typ_num_ordre_1`, `typ_num_ordre_2`, `typ_num_ordre_3`, `typ_num_ordre_4`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Définition de la population pour laquelle on peut agir';
/*--------------------------------------*/


/*------------------------------------
Création table iin_idx_indiv
Issue de iin_idx_indiv
-------------------------------------*/
DROP TABLE IF EXISTS iin_idx_indiv;
CREATE TABLE `iin_idx_indiv` (
  `iin_id` INT NOT NULL AUTO_INCREMENT,
  `iin_id_code_client` VARCHAR(20) NOT NULL,
  `iin_id_code_societe` VARCHAR(255) NOT NULL,
  `iin_id_code_jalon` VARCHAR(5) NOT NULL,
  `iin_id_code_etablissement` VARCHAR(10) NOT NULL,
  `iin_id_lib_etablissement` VARCHAR(255) NOT NULL,
  `iin_id_nom_societe` VARCHAR(255) NOT NULL,
  `iin_id_nom_client` VARCHAR(255) NOT NULL,
  `iin_id_type_paie` VARCHAR(5) DEFAULT NULL,
  `iin_id_periode_paie` VARCHAR(10) NOT NULL,
  `iin_id_nom_salarie` VARCHAR(255) NOT NULL,
  `iin_id_prenom_salarie` VARCHAR(255) NOT NULL,
  `iin_id_nom_jeune_fille_salarie` VARCHAR(255) DEFAULT NULL,
  `iin_id_date_entree` DATETIME DEFAULT NULL,
  `iin_id_date_sortie` DATETIME DEFAULT NULL,
  `iin_id_num_nir` VARCHAR(30) NOT NULL,
  `iin_id_num_matricule` VARCHAR(10) NOT NULL,
  `iin_fichier_index` VARCHAR(50) NOT NULL,
  `iin_id_code_categ_professionnelle` VARCHAR(255) NOT NULL,
  `iin_id_code_categ_socio_prof` VARCHAR(255) NOT NULL,
  `iin_id_type_contrat` VARCHAR(255) NOT NULL,
  `iin_id_affectation1` VARCHAR(255) NOT NULL,
  `iin_id_affectation2` VARCHAR(255) NOT NULL,
  `iin_id_affectation3` VARCHAR(255) NOT NULL,
  `iin_id_num_siren` VARCHAR(255) NOT NULL,
  `iin_id_num_siret` VARCHAR(255) NOT NULL,
  `iin_id_date_naissance` DATETIME NOT NULL,
  `iin_id_libre1` VARCHAR(255) DEFAULT NULL,
  `iin_id_libre2` VARCHAR(255) DEFAULT NULL,
  `iin_id_num_matricule_groupe` VARCHAR(255) DEFAULT NULL,
  `iin_id_num_matricule_rh` VARCHAR(255) NOT NULL,
  `iin_id_code_activite` VARCHAR(255) NOT NULL,
  `iin_id_code_chrono` VARCHAR(20) DEFAULT NULL,
  `iin_id_date_1` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_2` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_3` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_4` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_5` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_6` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_7` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_8` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_adp_1` VARCHAR(8) DEFAULT NULL,
  `iin_id_date_adp_2` VARCHAR(8) DEFAULT NULL,
  `iin_id_alphanum_1` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_2` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_3` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_4` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_5` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_6` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_7` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_8` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_9` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_10` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_11` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_12` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_13` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_14` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_15` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_16` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_17` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_18` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_adp_1` VARCHAR(50) DEFAULT NULL,
  `iin_id_alphanum_adp_2` VARCHAR(50) DEFAULT NULL,
  `iin_id_num_1` FLOAT DEFAULT NULL,
  `iin_id_num_2` FLOAT DEFAULT NULL,
  `iin_id_num_3` FLOAT DEFAULT NULL,
  `iin_id_num_4` FLOAT DEFAULT NULL,
  `iin_id_num_5` FLOAT DEFAULT NULL,
  `iin_id_num_6` FLOAT DEFAULT NULL,
  `iin_id_num_7` FLOAT DEFAULT NULL,
  `iin_id_num_8` FLOAT DEFAULT NULL,
  `iin_id_num_9` FLOAT DEFAULT NULL,
  `iin_id_num_10` FLOAT DEFAULT NULL,
  `iin_id_num_ordre` VARCHAR(255) DEFAULT NULL,
  `iin_created_at` DATETIME,
  `iin_updated_at` DATETIME,
  PRIMARY KEY (`iin_id`),
  UNIQUE KEY `iin_id_num_matricule` (`iin_id_num_matricule`,`iin_id_code_client`),
  KEY `iin_id_nom_salarie` (`iin_id_nom_salarie`),
  KEY `iin_id_prenom_salarie` (`iin_id_prenom_salarie`), -- recherche que par prénom ? sinon créer clé composée (nom,prenom)
--   KEY `iin_id_type_contrat` (`iin_id_type_contrat`), -- cardinalité 6
  KEY `iin_id_code_categ_socio_prof` (`iin_id_code_categ_socio_prof`),
  KEY `iin_id_code_categ_professionnelle` (`iin_id_code_categ_professionnelle`),
  KEY `iin_id_code_etablissement` (`iin_id_code_etablissement`),
--  KEY `iin_id_periode_paie` (`iin_id_periode_paie`), -- cardinalité 6
  KEY `iin_id_nom_jeune_fille_salarie` (`iin_id_nom_jeune_fille_salarie`),
  KEY `iin_id_date_entree` (`iin_id_date_entree`),
  KEY `iin_id_date_sortie` (`iin_id_date_sortie`),
  KEY `iin_id_num_nir` (`iin_id_num_nir`),
  KEY `iin_id_lib_etablissement` (`iin_id_lib_etablissement`),
--  KEY `iin_id_code_societe` (`iin_id_code_societe`), -- cardinalité 6
  KEY `iin_id_nom_societe` (`iin_id_nom_societe`),
  KEY `iin_id_date_naissance` (`iin_id_date_naissance`),
--  KEY `iin_id_num_siren` (`iin_id_num_siren`), -- cardinalité 6
  KEY `iin_id_num_siret` (`iin_id_num_siret`),
  KEY `iin_id_num_matricule_rh` (`iin_id_num_matricule_rh`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tous les salariés des clients docapost';
/*--------------------------------------*/


/*------------------------------------
Création table ifp_indexfiche_paperless
Issu de indexfiche_paperless
-------------------------------------*/
DROP TABLE IF EXISTS ifp_indexfiche_paperless;
CREATE TABLE `ifp_indexfiche_paperless` (
  `ifp_id` INT NOT NULL AUTO_INCREMENT,
  `ifp_documentsassocies` VARCHAR(40),
  `ifp_vdm_localisation` TEXT NOT NULL,
  `ifp_refpapier` VARCHAR(4) DEFAULT NULL,
  `ifp_nombrepages` SMALLINT DEFAULT NULL,
  `ifp_id_code_chrono` VARCHAR(255) NOT NULL,
  `ifp_id_numero_boite_archive` VARCHAR(255) NOT NULL,
  `ifp_interbox` BOOL NOT NULL DEFAULT 0,
  `ifp_lot_index` VARCHAR(100) NOT NULL,
  `ifp_lot_production` SMALLINT NOT NULL,
  `ifp_id_nom_societe` VARCHAR(255) DEFAULT NULL,
  `ifp_id_company` VARCHAR(255) DEFAULT NULL,
  `ifp_id_nom_client` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_client` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_societe` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_etablissement` VARCHAR(255) DEFAULT NULL,
  `ifp_id_lib_etablissement` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_jalon` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libelle_jalon` VARCHAR(255) NOT NULL,
  `ifp_id_num_siren` VARCHAR(255) DEFAULT NULL,
  `ifp_id_num_siret` VARCHAR(255) DEFAULT NULL,
  `ifp_id_indice_classement` VARCHAR(255) DEFAULT NULL,
  `ifp_id_unique_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_type_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libelle_document` VARCHAR(255) NOT NULL,
--  `ifp_id_code_document` varchar(10) NOT NULL,
  `ifp_id_code_document` VARCHAR(10) NULL,
  `ifp_id_format_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_auteur_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_source_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_num_version_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_poids_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_nbr_pages_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_profil_archivage` VARCHAR(255) DEFAULT NULL,
  `ifp_id_etat_archive` VARCHAR(255) DEFAULT NULL,
  `ifp_id_periode_paie` VARCHAR(255) DEFAULT NULL,
  `ifp_id_periode_exercice_sociale` VARCHAR(255) DEFAULT NULL,
  `ifp_id_date_dernier_acces_document` DATETIME DEFAULT NULL,
  `ifp_id_date_archivage_document` DATETIME DEFAULT NULL,
  `ifp_id_duree_archivage_document` VARCHAR(255) DEFAULT NULL,
  `ifp_id_date_fin_archivage_document` DATETIME DEFAULT NULL,
  `ifp_id_nom_salarie` VARCHAR(255) DEFAULT NULL,
  `ifp_id_prenom_salarie` VARCHAR(255) DEFAULT NULL,
  `ifp_id_nom_jeune_fille_salarie` VARCHAR(255) DEFAULT NULL,
  `ifp_id_date_naissance_salarie` DATETIME DEFAULT NULL,
  `ifp_id_date_entree` DATETIME DEFAULT NULL,
  `ifp_id_date_sortie` DATETIME DEFAULT NULL,
  `ifp_id_num_nir` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_categ_professionnelle` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_categ_socio_prof` VARCHAR(255) DEFAULT NULL,
  `ifp_id_type_contrat` VARCHAR(255) DEFAULT NULL,
  `ifp_id_affectation1` VARCHAR(255) DEFAULT NULL,
  `ifp_id_affectation2` VARCHAR(255) DEFAULT NULL,
  `ifp_id_affectation3` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libre1` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libre2` VARCHAR(255) DEFAULT NULL,
  `ifp_id_affectation1_date` VARCHAR(255) DEFAULT NULL,
  `ifp_id_affectation2_date` VARCHAR(255) DEFAULT NULL,
  `ifp_id_affectation3_date` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libre1_date` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libre2_date` VARCHAR(255) DEFAULT NULL,
  `ifp_id_num_matricule` VARCHAR(255) DEFAULT NULL,
  `ifp_id_num_matricule_rh` VARCHAR(255) NOT NULL,
  `ifp_id_num_matricule_groupe` VARCHAR(255) NOT NULL,
  `ifp_id_annotation` TEXT NOT NULL,
  `ifp_id_conteneur` VARCHAR(255) NOT NULL,
  `ifp_id_boite` VARCHAR(255) NOT NULL,
  `ifp_id_lot` VARCHAR(255) NOT NULL,
  `ifp_id_num_ordre` VARCHAR(2) NOT NULL,
  `ifp_archive_cfec` TEXT NOT NULL,
  `ifp_archive_serialnumber` TEXT NOT NULL,
  `ifp_archive_datetime` TEXT NOT NULL,
  `ifp_archive_name` TEXT NOT NULL,
  `ifp_archive_cfe` TEXT NOT NULL,
  `ifp_numeropdf` VARCHAR(50) NOT NULL,
  `ifp_opn_provenance` VARCHAR(100) NOT NULL,
  `ifp_status_num` VARCHAR(10) NOT NULL DEFAULT 'OK',
  `ifp_is_doublon` BOOL NOT NULL DEFAULT 0,
  `ifp_login` VARCHAR(100) NOT NULL,
  `ifp_modedt` VARCHAR(20) NOT NULL,
  `ifp_numdtr` VARCHAR(20) NOT NULL,
  `ifp_old_id_date_dernier_acces_document` VARCHAR(255) DEFAULT NULL,
  `ifp_old_id_date_archivage_document` VARCHAR(255) DEFAULT NULL,
  `ifp_old_id_date_fin_archivage_document` VARCHAR(255) DEFAULT NULL,
  `ifp_old_id_date_naissance_salarie` TEXT DEFAULT NULL,
  `ifp_old_id_date_entree` VARCHAR(255) DEFAULT NULL,
  `ifp_old_id_date_sortie` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_activite` VARCHAR(255) NOT NULL,
  `ifp_cycle_fin_de_vie` BOOL NOT NULL DEFAULT 0,
  `ifp_cycle_purger` VARCHAR(1) DEFAULT NULL,
  `ifp_geler` BOOL NOT NULL DEFAULT 0,
  `ifp_id_date_1` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_2` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_3` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_4` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_5` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_6` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_7` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_8` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_adp_1` VARCHAR(8) DEFAULT NULL,
  `ifp_id_date_adp_2` VARCHAR(8) DEFAULT NULL,
  `ifp_id_alphanum_1` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_2` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_3` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_4` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_5` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_6` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_7` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_8` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_9` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_10` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_11` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_12` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_13` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_14` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_15` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_16` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_17` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_18` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_adp_1` VARCHAR(50) DEFAULT NULL,
  `ifp_id_alphanum_adp_2` VARCHAR(50) DEFAULT NULL,
  `ifp_id_num_1` FLOAT DEFAULT NULL,
  `ifp_id_num_2` FLOAT DEFAULT NULL,
  `ifp_id_num_3` FLOAT DEFAULT NULL,
  `ifp_id_num_4` FLOAT DEFAULT NULL,
  `ifp_id_num_5` FLOAT DEFAULT NULL,
  `ifp_id_num_6` FLOAT DEFAULT NULL,
  `ifp_id_num_7` FLOAT DEFAULT NULL,
  `ifp_id_num_8` FLOAT DEFAULT NULL,
  `ifp_id_num_9` FLOAT DEFAULT NULL,
  `ifp_id_num_10` FLOAT DEFAULT NULL,
  `ifp_cycle_temps_parcouru` VARCHAR(50) DEFAULT NULL,
  `ifp_cycle_temps_restant` VARCHAR(50) DEFAULT NULL,
  `ifp_set_fin_archivage` BOOL NOT NULL DEFAULT 0,
-- Ajout Alexandre le 29/01/2016 (MPE)
  `ifp_is_personnel` BOOL NOT NULL DEFAULT 0,
  `ifp_created_at` DATETIME,
  `ifp_updated_at` DATETIME,
  PRIMARY KEY (`ifp_id`),
  KEY `ifp_nombrepages` (`ifp_nombrepages`),
-- modif C.Reboul le 03/12/2015
  KEY `ifp_id_code_document_societe_jalon` (`ifp_id_code_document`,`ifp_id_code_societe`,`ifp_id_code_jalon`),
-- Ajout CR le 03/12/2015
  KEY ifp_mat_cli_doc (`ifp_id_num_matricule`,`ifp_id_code_client`,`ifp_id_code_document`),
  KEY `ifp_id_indice_classement` (`ifp_id_indice_classement`),
  KEY `ifp_id_unique_document` (`ifp_id_unique_document`),
  KEY `ifp_id_type_document` (`ifp_id_type_document`),
  KEY `ifp_id_poids_document` (`ifp_id_poids_document`),
  KEY `ifp_id_nbr_pages_document` (`ifp_id_nbr_pages_document`),
  KEY `ifp_id_periode_paie` (`ifp_id_periode_paie`),
  KEY `ifp_id_periode_exercice_sociale` (`ifp_id_periode_exercice_sociale`),
  KEY `ifp_id_date_dernier_acces_document` (`ifp_id_date_dernier_acces_document`),
  KEY `ifp_id_date_archivage_document` (`ifp_id_date_archivage_document`),
  KEY `ifp_id_date_fin_archivage_document` (`ifp_id_date_fin_archivage_document`),
  KEY `ifp_id_nom_salarie` (`ifp_id_nom_salarie`),
  KEY `ifp_id_prenom_salarie` (`ifp_id_prenom_salarie`),
  KEY `ifp_id_nom_jeune_fille_salarie` (`ifp_id_nom_jeune_fille_salarie`),
  KEY `ifp_id_date_entree` (`ifp_id_date_entree`),
  KEY `ifp_id_date_sortie` (`ifp_id_date_sortie`),
  KEY `ifp_id_num_nir` (`ifp_id_num_nir`),
  KEY `ifp_id_code_categ_professionnelle` (`ifp_id_code_categ_professionnelle`),
  KEY `ifp_id_libre1` (`ifp_id_libre1`),
  KEY `ifp_id_libre1_date` (`ifp_id_libre1_date`),
  KEY `ifp_modedt` (`ifp_modedt`),
  KEY `ifp_numdtr` (`ifp_numdtr`),
  KEY `ifp_id_libelle_doc_code_doc` (`ifp_id_libelle_document`,`ifp_id_code_document`),
  KEY `id_numero_boite_archive` (`ifp_id_numero_boite_archive`),
  KEY `ifp_id_code_activite` (`ifp_id_code_activite`),
  KEY `ifp_id_num_matricule_rh` (`ifp_id_num_matricule_rh`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Toutes les infos portant sur les documents transmis et archivés IMPORTANT : gérer statut et no de lot.';
/*--------------------------------------*/


/*------------------------------------
Création table rim_rapport_import
Issu de rapport_import
-------------------------------------*/
DROP TABLE IF EXISTS rim_rapport_import;
CREATE TABLE `rim_rapport_import` (
  `rim_id` INT NOT NULL AUTO_INCREMENT,
  `rim_id_user` INT NOT NULL,
  `rim_text_rapport` BLOB,
  `rim_titre` VARCHAR(255) DEFAULT NULL,
  `rim_etat` VARCHAR(2) DEFAULT NULL,
  `rim_created_at` DATETIME,
  `rim_updated_at` DATETIME,
  PRIMARY KEY (`rim_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Rapports des imports de masse';
/*--------------------------------------*/


/*------------------------------------
Création table rre_rapport_recherche
Issu de rapport_recherche
-------------------------------------*/
DROP TABLE IF EXISTS rre_rapport_recherche;
CREATE TABLE `rre_rapport_recherche` (
  `rre_id` INT NOT NULL AUTO_INCREMENT,
  `rre_date_ajout` DATETIME NOT NULL,
  `rre_login` VARCHAR(100) NOT NULL,
  `rre_contenu_fichier` LONGTEXT NOT NULL,
  `rre_created_at` DATETIME,
  `rre_updated_at` DATETIME,
  PRIMARY KEY (`rre_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Rapport des exports de plus de x lignes';
/*--------------------------------------*/


/*------------------------------------
Création table ian_anomalies
Issu de idx_anomalies
-------------------------------------*/
DROP TABLE IF EXISTS ian_anomalies;
CREATE TABLE `ian_anomalies` (
  `ian_id` INT NOT NULL AUTO_INCREMENT,
  `ian_anomalie` VARCHAR(100) NOT NULL,
  `ian_date` DATE NOT NULL,
  `ian_ref_adp` VARCHAR(50) DEFAULT NULL,
  `ian_type` VARCHAR(50) DEFAULT NULL,
  `ian_matricule` VARCHAR(50) DEFAULT NULL,
  `ian_etat_archive` VARCHAR(50) DEFAULT NULL,
  `ian_fichier` VARCHAR(255) NOT NULL,
  `ian_indiv` BOOL NOT NULL DEFAULT TRUE,
  `ian_nom_prenom` VARCHAR(255) DEFAULT NULL,
  `ian_created_at` DATETIME,
  `ian_updated_at` DATETIME,
  PRIMARY KEY (`ian_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Répertorie les anomalies traitées par les chaînes';
/*--------------------------------------*/


/*------------------------------------
Création table générique des ref_ID, rid_ref_id
Issue de :
ref_ID_AFFECTATION1
ref_ID_AFFECTATION2
ref_ID_AFFECTATION3
ref_ID_CODE_ACTIVITE
ref_ID_CODE_CATEG_PROFESSIONNELLE
ref_ID_CODE_CATEG_SOCIO_PROF
ref_ID_CODE_CLIENT
ref_ID_CODE_DOCUMENT
ref_ID_LIBRE1
ref_ID_LIBRE2
ref_ID_TYPE_CONTRAT
-------------------------------------*/
DROP TABLE IF EXISTS rid_ref_id;
CREATE TABLE `rid_ref_id` (
  `rid_code` VARCHAR(50) NOT NULL,
  `rid_libelle` VARCHAR(255) NOT NULL,
  `rid_id_code_client` VARCHAR(20) NOT NULL,
  `rid_type` VARCHAR(30) NOT NULL,
  `rid_created_at` DATETIME,
  `rid_updated_at` DATETIME,
  PRIMARY KEY (`rid_code`,`rid_id_code_client`,`rid_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Les ref_ID sont les flux remplis par le client (filtres)';
/*--------------------------------------*/


/*------------------------------------
Création table rcs_ref_code_societe
Issu de ref_ID_CODE_SOCIETE
-------------------------------------*/
DROP TABLE IF EXISTS rcs_ref_code_societe;
CREATE TABLE `rcs_ref_code_societe` (
  `rcs_code` VARCHAR(50) NOT NULL,
  `rcs_libelle` VARCHAR(255) NOT NULL,
  `rcs_siren` VARCHAR(9) NOT NULL,
  `rcs_id_code_client` VARCHAR(20) NOT NULL,
  `rcs_created_at` DATETIME,
  `rcs_updated_at` DATETIME,
  PRIMARY KEY (`rcs_code`, `rcs_id_code_client`),
  KEY `rcs_id_code_client` (`rcs_id_code_client`),
  KEY `rcs_libelle` (`rcs_libelle`),
  UNIQUE KEY `rcs_siren` (`rcs_siren`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flux des sociétés remplis par le client';
/*--------------------------------------*/


/*------------------------------------
Création table rce_ref_code_etablissement
Issu de ref_ID_CODE_ETABLISSEMENT
-------------------------------------*/
DROP TABLE IF EXISTS rce_ref_code_etablissement;
CREATE TABLE `rce_ref_code_etablissement` (
  `rce_code` VARCHAR(50) NOT NULL,
  `rce_libelle` VARCHAR(255) NOT NULL,
  `rce_siren` VARCHAR(9) NOT NULL,
  `rce_nic` VARCHAR(5) NOT NULL,
  `rce_id_code_client` VARCHAR(20) NOT NULL,
  `rce_created_at` DATETIME,
  `rce_updated_at` DATETIME,
  PRIMARY KEY (`rce_code`, `rce_id_code_client`),
  KEY `rce_id_code_client` (`rce_id_code_client`),
  KEY `rce_libelle` (`rce_libelle`),
  UNIQUE KEY `rce_siret` (`rce_siren`, `rce_nic`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flux des établissements remplis par le client';
/*--------------------------------------*/


/*------------------------------------
Création table trace_habilitation_masse
Issue de trace_habilitation_masse
-------------------------------------*/
DROP TABLE IF EXISTS thm_trace_habilitation_masse;
CREATE TABLE `thm_trace_habilitation_masse` (
  `thm_id` INT NOT NULL AUTO_INCREMENT,
  `thm_date` DATETIME NOT NULL,
  `thm_traite` SMALLINT NOT NULL,
  `thm_succes` SMALLINT NOT NULL,
  `thm_erreur` SMALLINT NOT NULL,
  `thm_rapport` TEXT,
  `thm_created_at` DATETIME,
  `thm_updated_at` DATETIME,
  PRIMARY KEY (`thm_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Logs suite à l''import des habilitations de masse';
/*--------------------------------------*/


/*------------------------------------
Création table aso_alerte_securite_opn
Issue de vdm_alerte_securite_opn
-------------------------------------*/
DROP TABLE IF EXISTS aso_alerte_securite_opn;
CREATE TABLE `aso_alerte_securite_opn` (
  `aso_id` INT NOT NULL AUTO_INCREMENT,
  `aso_referer` TEXT NOT NULL,
  `aso_machine` TEXT NOT NULL,
  `aso_request_method` TEXT NOT NULL,
  `aso_request_uri` TEXT NOT NULL,
  `aso_created_at` DATETIME,
  `aso_updated_at` DATETIME,
  PRIMARY KEY (`aso_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Traçage d''alerte quand import ne marche pas.';
/*--------------------------------------*/


/*------------------------------------
Création table ano_annotations
Issue de vdm_annotations
-------------------------------------*/
DROP TABLE IF EXISTS ano_annotations;
CREATE TABLE `ano_annotations` (
  `ano_id` INT NOT NULL AUTO_INCREMENT,
  `ano_fiche` INT NOT NULL,
  `ano_login` VARCHAR(100) NOT NULL,
  `ano_etat` VARCHAR(10) NOT NULL DEFAULT 'ACTIVE',
  `ano_statut` VARCHAR(10) NOT NULL DEFAULT 'PRIVEE',
  `ano_texte` TEXT NOT NULL,
  `ano_created_at` DATETIME,
  `ano_updated_at` DATETIME,
  PRIMARY KEY (`ano_id`),
  KEY `ano_fiche` (`ano_fiche`),
  KEY `ano_login` (`ano_login`),
  KEY `ano_created_at` (`ano_created_at`),
  KEY `ano_etat` (`ano_etat`),
  KEY `ano_statut` (`ano_statut`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table ado_annotations_dossier
Issue de vdm_annotations_dossier
-------------------------------------*/
DROP TABLE IF EXISTS ado_annotations_dossier;
CREATE TABLE `ado_annotations_dossier` (
  `ado_id` INT NOT NULL AUTO_INCREMENT,
  `ado_folder` INT NOT NULL,
  `ado_login` VARCHAR(100) NOT NULL,
  `ado_etat` VARCHAR(10) NOT NULL DEFAULT 'ACTIVE',
  `ado_statut` VARCHAR(10) NOT NULL DEFAULT 'PRIVEE',
  `ado_texte` TEXT NOT NULL,
  `ado_created_at` DATETIME,
  `ado_updated_at` DATETIME,
  PRIMARY KEY (`ado_id`),
  KEY `ado_folder` (`ado_folder`),
  KEY `ado_login` (`ado_login`),
  KEY `ado_created_at` (`ado_created_at`),
  KEY `ado_etat` (`ado_etat`),
  KEY `ado_statut` (`ado_statut`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table edi_edition
Issue de vdm_edition
-------------------------------------*/
DROP TABLE IF EXISTS edi_edition;
CREATE TABLE `edi_edition` (
  `edi_id` INT NOT NULL AUTO_INCREMENT,
  `edi_fiche` INT NOT NULL,
  `edi_user_login` VARCHAR(50) NOT NULL,
  `edi_created_at` DATETIME,
  `edi_updated_at` DATETIME,
  PRIMARY KEY (`edi_id`),
  KEY `edi_user_login` (`edi_user_login`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table fdp_fdp
Issue de vdm_fdp
-------------------------------------*/
DROP TABLE IF EXISTS fdp_fdp;
CREATE TABLE `fdp_fdp` (
  `fdp_id` INT NOT NULL AUTO_INCREMENT,
  `fdp_nom` VARCHAR(100) NOT NULL,
  `fdp_id_application` VARCHAR(100) NOT NULL,
  `fdp_id_fdp` VARCHAR(100) NOT NULL,
  `fdp_chemin_on` VARCHAR(255) DEFAULT NULL,
  `fdp_chemin_off` VARCHAR(255) DEFAULT NULL,
  `fdp_x` SMALLINT NOT NULL DEFAULT 0,
  `fdp_y` SMALLINT NOT NULL DEFAULT 0,
  `fdp_scale_x` SMALLINT NOT NULL DEFAULT 100,
  `fdp_scale_y` SMALLINT NOT NULL DEFAULT 100,
  `fdp_rotation` SMALLINT NOT NULL DEFAULT 0,
  `fdp_cgv` VARCHAR(255) DEFAULT NULL,
  `fdp_as_chemin_on` VARCHAR(255) DEFAULT NULL,
  `fdp_as_x` FLOAT NOT NULL DEFAULT '0',
  `fdp_as_y` FLOAT NOT NULL DEFAULT '0',
  `fdp_as_scale_x` SMALLINT NOT NULL DEFAULT 0,
  `fdp_as_scale_y` SMALLINT NOT NULL DEFAULT 0,
  `fdp_as_rotation` SMALLINT NOT NULL DEFAULT 0,
  `fdp_created_at` DATETIME,
  `fdp_updated_at` DATETIME,
  PRIMARY KEY (`fdp_id`),
  UNIQUE KEY `fdp_nom_appli` (`fdp_nom`,`fdp_id_application`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste des fonds de page';
/*--------------------------------------*/


/*------------------------------------
Création table icf_info_cfec
Issue de vdm_info_cfec
-------------------------------------*/
DROP TABLE IF EXISTS icf_info_cfec;
CREATE TABLE `icf_info_cfec` (
  `icf_id` INT NOT NULL AUTO_INCREMENT,
  `icf_id_alias` VARCHAR(100) NOT NULL,
  `icf_cfec_base_principal` VARCHAR(255) NOT NULL,
  `icf_cfec_cert_principal` VARCHAR(255) NOT NULL,
  `icf_cfec_numero_cfe_principal` VARCHAR(10) NOT NULL,
  `icf_cfec_numero_cfec_principal` VARCHAR(10) NOT NULL,
  `icf_cfec_base_secondaire` VARCHAR(255) NOT NULL,
  `icf_cfec_cert_secondaire` VARCHAR(255) NOT NULL,
  `icf_cfec_numero_cfec_secondaire` VARCHAR(10) NOT NULL,
  `icf_cfec_numero_cfe_secondaire` VARCHAR(10) NOT NULL,
  `icf_ci_url_principal` VARCHAR(255) NOT NULL,
  `icf_ci_rep_transfer_principal` VARCHAR(255) NOT NULL,
  `icf_ci_rep_cr_principal` VARCHAR(255) NOT NULL,
  `icf_ci_url_secondaire` VARCHAR(255) NOT NULL,
  `icf_ci_rep_transfer_secondaire` VARCHAR(255) NOT NULL,
  `icf_ci_rep_cr_secondaire` VARCHAR(255) NOT NULL,
  `icf_created_at` DATETIME,
  `icf_updated_at` DATETIME,
  PRIMARY KEY (`icf_id`),
  UNIQUE KEY `icf_id_alias` (`icf_id_alias`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Utilisée, infos de coffre utilisée par les étapes de traitement, une ligne par instance';
/*--------------------------------------*/


/*------------------------------------
Création table iup_interupload
Issue de vdm_interupload
-------------------------------------*/
DROP TABLE IF EXISTS iup_interupload;
CREATE TABLE `iup_interupload` (
  `iup_id` INT NOT NULL AUTO_INCREMENT,
  `iup_ticket` VARCHAR(72) NOT NULL,
  `iup_challenge` VARCHAR(72) NULL,
  `iup_statut` VARCHAR(100) NULL,
  `iup_date_production` DATETIME NULL,
  `iup_date_archivage` DATETIME NULL,
  `iup_config` TEXT NULL,
  `iup_metadata_creation` TEXT NULL,
  `iup_metadata_production` TEXT NULL,
  `iup_metadata_archivage` TEXT NULL,
  `iup_created_at` DATETIME,
  `iup_updated_at` DATETIME,
  PRIMARY KEY (`iup_id`),
  UNIQUE KEY `iup_ticket` (`iup_ticket`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table iuc_interupload_cfg
Issue de vdm_interupload_cfg
-------------------------------------*/
DROP TABLE IF EXISTS iuc_interupload_cfg;
CREATE TABLE `iuc_interupload_cfg` (
  `iuc_id` INT NOT NULL AUTO_INCREMENT,
  `iuc_id_interupload` VARCHAR(100) NOT NULL COMMENT 'ID de la configuration Interupload',
  `iuc_codeClient` VARCHAR(100) NOT NULL COMMENT 'Code Client attaché à cette upload',
  `iuc_codeAppli` VARCHAR(100) NOT NULL COMMENT 'Code Application attaché à cet upload',
  `iuc_param` VARCHAR(100) NOT NULL COMMENT 'Code param ici l''url du webservice du site',
  `iuc_config` TEXT NOT NULL COMMENT 'configuration XML',
  `iuc_script_archivage_specifique` VARCHAR(100) NOT NULL COMMENT 'script php d''archivage se trouvant dans spécifique ''x_interupload.php'' dans spécifique, si vide utilise le script générique ',
  `iuc_interuploadWeb` VARCHAR(255) NOT NULL COMMENT 'webservice interuploadProduction pour la partie Web',
  `iuc_versionApplet` VARCHAR(10) NOT NULL DEFAULT '0.27',
  `iuc_id_upload` INT NOT NULL DEFAULT 0,
  `iuc_created_at` DATETIME,
  `iuc_updated_at` DATETIME,
  PRIMARY KEY (`iuc_id`),
  UNIQUE KEY `iuc_id_interupload` (`iuc_id_interupload`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table la0_langue0
Issue de vdm_langue0
-------------------------------------*/
DROP TABLE IF EXISTS la0_langue0;
CREATE TABLE `la0_langue0` (
  `la0_id` INT NOT NULL AUTO_INCREMENT,
  `la0_variable` VARCHAR(255) NOT NULL,
  `la0_valeur` TEXT NOT NULL,
  `la0_created_at` DATETIME,
  `la0_updated_at` DATETIME,
  PRIMARY KEY (`la0_id`),
  UNIQUE KEY `la0_variable` (`la0_variable`),
  KEY `la0_date` (`la0_created_at`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Conversion en français';
/*--------------------------------------*/


/*------------------------------------
Création table la1_langue1
Issue de vdm_langue1
-------------------------------------*/
DROP TABLE IF EXISTS la1_langue1;
CREATE TABLE `la1_langue1` (
  `la1_id` INT NOT NULL AUTO_INCREMENT,
  `la1_variable` VARCHAR(255) NOT NULL,
  `la1_valeur` TEXT NOT NULL,
  `la1_created_at` DATETIME,
  `la1_updated_at` DATETIME,
  PRIMARY KEY (`la1_id`),
  UNIQUE KEY `la1_variable` (`la1_variable`),
  KEY `la1_date` (`la1_created_at`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Conversion en anglais';
/*--------------------------------------*/


/*------------------------------------
Création table las_laserlike
Issue de vdm_laserlike
-------------------------------------*/
DROP TABLE IF EXISTS las_laserlike;
CREATE TABLE `las_laserlike` (
  `las_id` INT NOT NULL AUTO_INCREMENT,
  `las_debut` VARCHAR(20) DEFAULT NULL,
  `las_fin` VARCHAR(20) DEFAULT NULL,
  `las_chemin` VARCHAR(255) DEFAULT NULL,
  `las_libelle` VARCHAR(255) DEFAULT NULL,
  `las_id_application` VARCHAR(100) NOT NULL,
  `las_type` VARCHAR(10) NOT NULL,
  `las_cfec_base` TEXT NOT NULL,
  `las_cfec_cert` TEXT NOT NULL,
  `las_created_at` DATETIME,
  `las_updated_at` DATETIME,
  PRIMARY KEY (`las_id`),
  KEY `las_debut` (`las_debut`),
  KEY `las_fin` (`las_fin`),
  KEY `las_id_application` (`las_id_application`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table las_laserlike
Issue de vdm_mails
-------------------------------------*/
DROP TABLE IF EXISTS mai_mails;
CREATE TABLE `mai_mails` (
  `mai_id` INT NOT NULL AUTO_INCREMENT,
  `mai_user_login` VARCHAR(50) NOT NULL,
  `mai_mail_from` VARCHAR(255) DEFAULT NULL,
  `mai_mail_to` TEXT NOT NULL,
  `mai_mail_copy` TEXT NOT NULL,
  `mai_mail_hidden_copy` TEXT NOT NULL,
  `mai_mail_notify` BOOL NOT NULL DEFAULT 0,
  `mai_mail_copy_to_sender` BOOL NOT NULL DEFAULT 0,
  `mai_mail_subject` TEXT NOT NULL,
  `mai_mail_message` TEXT NOT NULL,
  `mai_mail_date` DATE NOT NULL DEFAULT '0000-00-00',
  `mai_mail_time` TIME NOT NULL DEFAULT '00:00:00',
  `mai_created_at` DATETIME,
  `mai_updated_at` DATETIME,
  PRIMARY KEY (`mai_id`),
  KEY `mai_user_login` (`mai_user_login`),
  KEY `mai_mail_date` (`mai_mail_date`),
  KEY `mai_mail_time` (`mai_mail_time`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table las_laserlike
Issue de vdm_preference
-------------------------------------*/
DROP TABLE IF EXISTS pre_preference;
CREATE TABLE `pre_preference` (
  `pre_nav` VARCHAR(100) NOT NULL,
  `pre_user` VARCHAR(50) NOT NULL,
  `pre_variable` TEXT NOT NULL,
  `pre_created_at` DATETIME,
  `pre_updated_at` DATETIME,
  PRIMARY KEY (`pre_nav`,`pre_user`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fourre-tout qui ressemble à une table de paramètres avec infos JSON';
/*--------------------------------------*/


/*------------------------------------
Création table prt_print
Issue de vdm_print
-------------------------------------*/
DROP TABLE IF EXISTS prt_print;
CREATE TABLE `prt_print` (
  `prt_id` INT NOT NULL AUTO_INCREMENT,
  `prt_user_login` VARCHAR(50) NOT NULL,
  `prt_fiche` INT NOT NULL,
  `prt_id_session` VARCHAR(100) DEFAULT NULL,
  `prt_created_at` DATETIME,
  `prt_updated_at` DATETIME,
  PRIMARY KEY (`prt_id`),
  KEY `prt_fiche` (`prt_fiche`)
--  KEY `prt_user_login` (`prt_user_login`) -- cardinalité 2
) ENGINE=INNODB COMMENT='Impression de documents';
/*--------------------------------------*/


/*------------------------------------
Création table ses_sessions_opn
Issue de vdm_sessions_opn
-------------------------------------*/
DROP TABLE IF EXISTS ses_sessions_opn;
CREATE TABLE `ses_sessions_opn` (
  `ses_id` INT NOT NULL AUTO_INCREMENT,
  `ses_id_session` VARCHAR(32) DEFAULT NULL,
  `ses_user_login` VARCHAR(50) NOT NULL,
  `ses_machine` TEXT NOT NULL,
  `ses_first_action` BIGINT NOT NULL DEFAULT '0',
  `ses_last_action` BIGINT NOT NULL DEFAULT '0',
  `ses_variables` TEXT NOT NULL,
  `ses_created_at` DATETIME,
  `ses_updated_at` DATETIME,
  PRIMARY KEY (`ses_id`),
  KEY `ses_id_session` (`ses_id_session`)
--  KEY `ses_user_login` (`ses_user_login`) -- cardinalité 4
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Sessions actives du site';
/*--------------------------------------*/


/*------------------------------------
Création table sto_stockage
Issue de vdm_stockage
-------------------------------------*/
DROP TABLE IF EXISTS sto_stockage;
CREATE TABLE `sto_stockage` (
  `sto_id` INT NOT NULL AUTO_INCREMENT,
  `sto_volume` VARCHAR(20) DEFAULT NULL,
  `sto_libelle` VARCHAR(50) DEFAULT NULL,
  `sto_chemin` VARCHAR(255) DEFAULT NULL,
  `sto_code_client` VARCHAR(4) DEFAULT NULL,
  `sto_code_application` VARCHAR(4) DEFAULT NULL,
  `sto_use_vis`BOOL NOT NULL DEFAULT 1,
  `sto_type` VARCHAR(10) NOT NULL DEFAULT 'STANDARD',
  `sto_cfec_base` VARCHAR(255) NOT NULL,
  `sto_cfec_cert` VARCHAR(100) NOT NULL,
  `sto_created_at` DATETIME,
  `sto_updated_at` DATETIME,
  PRIMARY KEY (`sto_id`),
  KEY `sto_volume` (`sto_volume`),
  KEY `sto_code_client` (`sto_code_client`),
  KEY `sto_code_application` (`sto_code_application`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Sert pour le VIS, stockage de documents';
/*--------------------------------------*/


/*------------------------------------
Création table spr_suivi_prod
Issue de vdm_suivi_prod
-------------------------------------*/
DROP TABLE IF EXISTS spr_suivi_prod;
CREATE TABLE `spr_suivi_prod` (
  `spr_id` INT NOT NULL AUTO_INCREMENT,
  `spr_fichier_dat` VARCHAR(50) DEFAULT NULL,
  `spr_numero_bdl` VARCHAR(20) NOT NULL,
  `spr_chrono_odin` INT NOT NULL DEFAULT 0,
  `spr_banniere_debut` VARCHAR(20) DEFAULT NULL,
  `spr_banniere_fin` VARCHAR(20) DEFAULT NULL,
  `spr_code_client` CHAR(4) DEFAULT NULL,
  `spr_code_application` CHAR(4) DEFAULT NULL,
  `spr_type_operation` VARCHAR(20) NOT NULL DEFAULT 'INJECTION',
  `spr_chrono_client` INT NOT NULL DEFAULT 0,
  `spr_spool` VARCHAR(20) DEFAULT NULL,
  `spr_fichier_log_empreinte` VARCHAR(255) DEFAULT NULL,
  `spr_max_enreg_avant` INT NOT NULL DEFAULT 0,
  `spr_nombre_enregs_avant` INT NOT NULL DEFAULT 0,
  `spr_max_enreg_apres` INT NOT NULL DEFAULT 0,
  `spr_nombre_enregs_apres` INT NOT NULL DEFAULT 0,
  `spr_nombre_pages_hebergees_avant` INT NOT NULL DEFAULT 0,
  `spr_nombre_pages_hebergees_apres` INT NOT NULL DEFAULT 0,
  `spr_nombre_dossiers` INT NOT NULL DEFAULT 0,
  `spr_nombre_pages` INT NOT NULL DEFAULT 0,
  `spr_nombre_pages_vides` INT NOT NULL DEFAULT 0,
  `spr_premier_index` VARCHAR(50) NOT NULL,
  `spr_dernier_index` VARCHAR(50) NOT NULL,
  `spr_temps_pdf` INT NOT NULL DEFAULT 0,
  `spr_temps_indexation` INT NOT NULL DEFAULT 0,
  `spr_temps_injection` INT NOT NULL DEFAULT 0,
  `spr_temps_autre` INT NOT NULL DEFAULT 0,
  `spr_compteur_dossiers_theorique` INT NOT NULL DEFAULT 0,
  `spr_compteur_pages_theorique` INT NOT NULL DEFAULT 0,
  `spr_table_indexfiche` VARCHAR(50) DEFAULT NULL,
  `spr_audit` TEXT NOT NULL,
  `spr_lot_index` VARCHAR(100) DEFAULT NULL,
  `spr_lot_production` INT NOT NULL DEFAULT 0,
  `spr_code_client_appelant` VARCHAR(10) DEFAULT NULL,
  `spr_code_application_appelant` VARCHAR(10) DEFAULT NULL,
  `spr_numerobdl_appelant` VARCHAR(20) NOT NULL,
  `spr_information_client` TEXT NOT NULL,
  `spr_created_at` DATETIME,
  `spr_updated_at` DATETIME,
  PRIMARY KEY (`spr_id`),
  KEY `spr_fichier_dat` (`spr_fichier_dat`),
  KEY `spr_numero_bdl` (`spr_numero_bdl`),
  KEY `spr_chrono_odin` (`spr_chrono_odin`),
  KEY `spr_max_enreg_avant` (`spr_max_enreg_avant`),
  KEY `spr_nombre_enregs_avant` (`spr_nombre_enregs_avant`),
  KEY `spr_max_enreg_apres` (`spr_max_enreg_apres`),
  KEY `spr_nombre_enregs_apres` (`spr_nombre_enregs_apres`),
  KEY `spr_nombre_dossiers` (`spr_nombre_dossiers`),
  KEY `spr_nombre_pages` (`spr_nombre_pages`),
  KEY `spr_created_at` (`spr_created_at`),
  KEY `spr_updated_at` (`spr_updated_at`),
  KEY `spr_lot_index` (`spr_lot_index`,`spr_lot_production`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table de suivi de production';
/*--------------------------------------*/


/*------------------------------------
Création table tam_tampons
Issue de vdm_tampons
-------------------------------------*/
DROP TABLE IF EXISTS tam_tampons;
CREATE TABLE `tam_tampons` (
  `tam_id` INT NOT NULL AUTO_INCREMENT,
  `tam_id_fdp` VARCHAR(100) NOT NULL,
  `tam_chemin` VARCHAR(255) DEFAULT NULL,
  `tam_x` INT NOT NULL DEFAULT 0,
  `tam_y` INT NOT NULL DEFAULT 0,
  `tam_scale_x` INT NOT NULL DEFAULT 100,
  `tam_scale_y` INT NOT NULL DEFAULT 100,
  `tam_rotation` INT NOT NULL DEFAULT 0,
  `tam_mode_overlay` BOOL NOT NULL DEFAULT 0,
  `tam_add_free_text` TEXT NOT NULL,
  `tam_as_x` INT NOT NULL DEFAULT 0,
  `tam_as_y` INT NOT NULL DEFAULT 0,
  `tam_as_scale_x` INT NOT NULL DEFAULT 0,
  `tam_as_scale_y` INT NOT NULL DEFAULT 0,
  `tam_as_rotation` INT NOT NULL DEFAULT 0,
  `tam_as_mode_overlay` BOOL NOT NULL DEFAULT 0,
  `tam_created_at` DATETIME,
  `tam_updated_at` DATETIME,
  PRIMARY KEY (`tam_id`),
  UNIQUE KEY `tam_id_fdp` (`tam_id_fdp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Options pour mettre des cadres pour visu';
/*--------------------------------------*/


/*------------------------------------
Création table tse_time_session
Issue de vdm_time_session
-------------------------------------*/
-- Test existance
DROP TABLE IF EXISTS tse_time_session;
CREATE TABLE `tse_time_session` (
  `tse_id` INT NOT NULL AUTO_INCREMENT,
  `tse_id_session` VARCHAR(32) NOT NULL,
  `tse_user_login` VARCHAR(50) NOT NULL,
  `tse_first_action` BIGINT NOT NULL,
  `tse_last_action` BIGINT NOT NULL,
  `tse_time_session` BIGINT NOT NULL,
  `tse_created_at` DATETIME,
  `tse_updated_at` DATETIME,
  PRIMARY KEY (`tse_id`),
  KEY `tse_user_login` (`tse_user_login`),
  KEY `tse_time_session` (`tse_time_session`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Logs sessions';
/*--------------------------------------*/


/*------------------------------------
Création table tcf_trace_cfec
Issue de vdm_trace_cfec
-------------------------------------*/
DROP TABLE IF EXISTS tcf_trace_cfec;
CREATE TABLE `tcf_trace_cfec` (
  `tcf_id` INT NOT NULL AUTO_INCREMENT,
  `tcf_nom_fichier` VARCHAR(255) NOT NULL,
  `tcf_num_fdr` VARCHAR(10) NOT NULL,
  `tcf_vdm_localisation` VARCHAR(50) NOT NULL,
  `tcf_nom_lot` VARCHAR(255) NOT NULL,
  `tcf_identifiant_unique` TEXT NOT NULL,
  `tcf_empreinte_archivage` TEXT NOT NULL,
  `tcf_archive_num_cfe` VARCHAR(10) NOT NULL,
  `tcf_archive_num_cfec` VARCHAR(10) NOT NULL,
  `tcf_archive_chemin_cfec` TEXT NOT NULL,
  `tcf_archive_serial_number` VARCHAR(255) NOT NULL,
  `tcf_archive_serial_number_2` VARCHAR(255) NOT NULL,
  `tcf_archive_datetime` VARCHAR(255) NOT NULL,
--  `tcf_chemin_cr_pec` text NOT NULL,
  `tcf_chemin_cr_pec` VARCHAR(255) NOT NULL,
  `tcf_date_depot` DATETIME NOT NULL,
  `tcf_created_at` DATETIME,
  `tcf_updated_at` DATETIME,
  PRIMARY KEY (`tcf_id`),
  KEY `tcf_vdm_localisation` (`tcf_vdm_localisation`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table nlg_nav_log
Issue de vdm_trace_opn
-------------------------------------*/
DROP TABLE IF EXISTS nlg_nav_log;
CREATE TABLE `nlg_nav_log` (
  `nlg_id` INT NOT NULL AUTO_INCREMENT,
  `nlg_nav` VARCHAR(50) DEFAULT NULL,
  `nlg_user` VARCHAR(50) DEFAULT NULL,
  `nlg_groupe` VARCHAR(255) DEFAULT NULL,
  `nlg_orsid` VARCHAR(1) DEFAULT NULL,
  `nlg_pages` INT NOT NULL DEFAULT 0,
  `nlg_octets` INT NOT NULL DEFAULT 0,
  `nlg_id_session` VARCHAR(50) DEFAULT NULL,
  `nlg_info` TEXT NOT NULL,
  `nlg_adresse_ip` VARCHAR(15) DEFAULT NULL,
  `nlg_user_agent` VARCHAR(255) DEFAULT NULL,
  `nlg_facturable` BOOL NOT NULL DEFAULT 1,
  `nlg_created_at` DATETIME,
  `nlg_updated_at` DATETIME,
  PRIMARY KEY (`nlg_id`),
  KEY `nlg_nav` (`nlg_nav`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Logs de navigation';
/*--------------------------------------*/


/*------------------------------------
Création table usr_users
Issue de vdm_users
-------------------------------------*/
DROP TABLE IF EXISTS usr_users;
CREATE TABLE `usr_users` (
  `usr_login` VARCHAR(50) NOT NULL,
  `usr_pass` VARCHAR(32) DEFAULT NULL,
  `usr_old_password_list` TEXT NOT NULL,
  `usr_change_pass` BOOL NOT NULL DEFAULT 0,
  `usr_pass_last_modif` BIGINT NOT NULL DEFAULT '0',
  `usr_pass_never_expires` BOOL NOT NULL DEFAULT 1,
  `usr_must_change_pass` BOOL NOT NULL DEFAULT 0,
  `usr_session_time` SMALLINT NOT NULL DEFAULT 1800,
  `usr_actif` BOOL NOT NULL DEFAULT 1,
  `usr_raison` TEXT NOT NULL,
  `usr_confidentialite` TEXT NOT NULL,
  `usr_prend_conf_groupe` BOOL NOT NULL DEFAULT 0,
  `usr_profils` VARCHAR(255) NOT NULL DEFAULT 'P1',
  `usr_type` VARCHAR(10) NOT NULL DEFAULT 'USR',
  `usr_statistiques` BOOL NOT NULL DEFAULT 0,
  `usr_orsid` BOOL NOT NULL DEFAULT 0,
  `usr_mail` BOOL NOT NULL DEFAULT 0,
  `usr_adresse_mail` TEXT NOT NULL,
  `usr_commentaires` TEXT NOT NULL,
  `usr_nom` TEXT NOT NULL,
  `usr_prenom` TEXT NOT NULL,
  `usr_adresse_post` TEXT NOT NULL,
  `usr_tel` TEXT NOT NULL,
  `usr_rsoc` TEXT NOT NULL,
  `usr_groupe` VARCHAR(255) NOT NULL DEFAULT 'PAPERLESS',
  `usr_export_csv` BOOL NOT NULL DEFAULT 0,
  `usr_export_xls` BOOL NOT NULL DEFAULT 0,
  `usr_export_doc` BOOL NOT NULL DEFAULT 0,
  `usr_recherche_llk` BOOL NOT NULL DEFAULT 0,
  `usr_usr_telechargements` BOOL NOT NULL DEFAULT 0,
  `usr_admin_tele` BOOL NOT NULL DEFAULT 0,
  `usr_tiers_archivage` BOOL NOT NULL DEFAULT 0,
  `usr_createur` VARCHAR(255) NOT NULL DEFAULT 'orsid',
  `usr_tentatives` SMALLINT NOT NULL DEFAULT '0',
  `usr_mail_cci_auto` BOOL NOT NULL DEFAULT 0,
  `usr_adresse_mail_cci_auto` TEXT NOT NULL,
  `usr_nb_panier` SMALLINT NOT NULL DEFAULT 10,
  `usr_nb_requete` SMALLINT NOT NULL DEFAULT 10,
  `usr_type_habilitation` VARCHAR(20) NOT NULL,
  `usr_envoyer` BOOL NOT NULL DEFAULT 0,
  `usr_modification` BOOL NOT NULL DEFAULT 0,
  `usr_suppression` BOOL NOT NULL DEFAULT 0,
  `usr_annotation` VARCHAR(2) NOT NULL DEFAULT 'N',
  `usr_classer` BOOL NOT NULL DEFAULT 0,
  `usr_recycler` BOOL NOT NULL DEFAULT 0,
  `usr_langue` VARCHAR(5) NOT NULL DEFAULT 'fr_FR',
  `usr_adp` BOOL NOT NULL DEFAULT 0,
  `usr_beginningdate` DATETIME NOT NULL,
  `usr_endingdate` DATETIME NOT NULL,
  `usr_status` VARCHAR(100) NOT NULL,
  `usr_annotation_dossier` VARCHAR(2) NOT NULL DEFAULT 'N',
  `usr_num_boite_archive` VARCHAR(25) DEFAULT NULL,
  `usr_cycle_de_vie` BOOL NOT NULL DEFAULT 0,
  `usr_mail_cycle_de_vie` VARCHAR(250) DEFAULT NULL,
  `usr_rapport_cel` BOOL DEFAULT NULL,
  `usr_created_at` DATETIME,
  `usr_updated_at` DATETIME,
  PRIMARY KEY (`usr_login`),
  UNIQUE KEY `usr_login_pass` (`usr_login`,`usr_pass`),
  KEY `usr_orsid` (`usr_orsid`),
  KEY `usr_groupe` (`usr_groupe`),
  KEY `usr_adp` (`usr_adp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Comptes utilisateurs avec leurs droits';
/*--------------------------------------*/


/*------------------------------------
Création table pro_processus
Issue de bve_processus, bve_processus_perso et bve_processus_perso_user
-------------------------------------*/
DROP TABLE IF EXISTS pro_processus;
CREATE TABLE `pro_processus` (
  `pro_id` INT NOT NULL AUTO_INCREMENT,
  `pro_groupe` SMALLINT NOT NULL,
  `pro_editable` BOOL NOT NULL DEFAULT 1,
  `pro_code` VARCHAR(3) NULL,
  `pro_libelle` VARCHAR(100) NULL,
  `pro_id_user` VARCHAR(50) NULL,
  `pro_created_at` DATETIME,
  `pro_updated_at` DATETIME,
  PRIMARY KEY (`pro_id`),
  KEY `pro_code` (`pro_code`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste des processus et processus perso.';
/*--------------------------------------*/


/*------------------------------------
Création table pty_processus_type
Issue de bve_processus_type et bve_processus_perso_type
-------------------------------------*/
DROP TABLE IF EXISTS pty_processus_type;
CREATE TABLE `pty_processus_type` (
  `pty_id` INT NOT NULL AUTO_INCREMENT,
  `pty_id_processus` INT NOT NULL,
  `pty_type` VARCHAR(10) NOT NULL,
  `pty_created_at` DATETIME,
  `pty_updated_at` DATETIME,
  PRIMARY KEY (`pty_id`),
  KEY `pty_id_pro_typ` (`pty_id_processus`, `pty_type`),
  KEY `pty_type` (`pty_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste des tiroirs pour les processus et processus perso.';
/*--------------------------------------*/


/*------------------------------------
Création table dic_dictionnaire
-------------------------------------*/
DROP TABLE IF EXISTS dic_dictionnaire;
CREATE TABLE `dic_dictionnaire` (
  `dic_id` INT NOT NULL AUTO_INCREMENT,
  `dic_support` VARCHAR(5) DEFAULT NULL,
  `dic_code` VARCHAR(100) DEFAULT NULL,
  `dic_valeur` VARCHAR(255) DEFAULT NULL,
  `dic_old_variable` VARCHAR(255) DEFAULT NULL,
  `dic_old_valeur` VARCHAR(255) DEFAULT NULL,
  `dic_old_provenance` VARCHAR(11) DEFAULT NULL,
  `dic_created_at` DATETIME,
  `dic_updated_at` DATETIME,
  PRIMARY KEY (`dic_id`),
  UNIQUE KEY `dic_support_code` (`dic_support`,`dic_code`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Dictionnaire des traductions';
/*--------------------------------------*/


/*------------------------------------
Création table dic_dictionnaire_translations
-------------------------------------*/
DROP TABLE IF EXISTS dic_dictionnaire_translations;
CREATE TABLE dic_dictionnaire_translations (
  `id` INT AUTO_INCREMENT NOT NULL,
  `locale` VARCHAR(8) NOT NULL,
  `object_class` VARCHAR(255) NOT NULL,
  `field` VARCHAR(32) NOT NULL,
  `foreign_key` VARCHAR(64) NOT NULL,
  `content` LONGTEXT DEFAULT NULL,
  INDEX dic_dictionnaire_translations_idx (locale, object_class, field, foreign_key),
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
/*--------------------------------------*/

/*--------------------------------------------------------------------*/
/*                                                                    */
/* Ajout des contraintes                                              */
/*                                                                    */
/*--------------------------------------------------------------------*/

SET FOREIGN_KEY_CHECKS=1;
/* Pour supprimer provisoirement toutes les contraintes*/
/*
SET FOREIGN_KEY_CHECKS=0;
set @err_fk_cty_com=0;
set @err_fk_cty_typ=0;
set @err_fk_fus_usr=0;
set @err_fk_fus_fol=0;
set @err_fk_fdo_fol=0;
*/
/*---------------------------------------------------*/

-- bve_completude -(n,1)-> vdm_users
-- les lignes orphelines ont été supprimées
ALTER TABLE com_completude ADD CONSTRAINT fk_com_usr FOREIGN KEY (com_id_user) REFERENCES usr_users(usr_login)
ON UPDATE CASCADE;

-- bve_completude_type -(n,1)-> bve_completude
-- les lignes orphelines ont été supprimées
ALTER TABLE cty_completude_type ADD CONSTRAINT fk_com_cty FOREIGN KEY (cty_id_completude) REFERENCES com_completude(com_id_completude)
ON DELETE CASCADE ON UPDATE CASCADE;

-- bve_completude_type -(n,1)-> bve_type
-- les lignes orphelines ont été supprimées
ALTER TABLE cty_completude_type ADD CONSTRAINT fk_typ_cty FOREIGN KEY (cty_type) REFERENCES typ_type(typ_code)
ON UPDATE CASCADE;

-- Alexandre, le 22 mars 2016
-- bve_profil_user -(1,1)-> bve_profil
-- les lignes orphelines ont été supprimées
ALTER TABLE pus_profil_user ADD CONSTRAINT fk_pus_pro FOREIGN KEY (pus_id_profil) REFERENCES pro_profil(pro_id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- Alexandre, le 22 mars 2016
-- bve_profil_user -(n,1)-> vdm_users
-- les lignes orphelines ont été supprimées
ALTER TABLE pus_profil_user ADD CONSTRAINT fk_pus_usr FOREIGN KEY (pus_id_user) REFERENCES usr_users(usr_login)
ON DELETE CASCADE ON UPDATE CASCADE;

-- bve_folder_user -(n,1)-> vdm_users
-- les lignes orphelines ont été supprimées
ALTER TABLE fus_folder_user ADD CONSTRAINT fk_fus_usr FOREIGN KEY (fus_id_user) REFERENCES usr_users(usr_login)
ON UPDATE CASCADE;

-- bve_folder_user -(n,1)-> bve_folder
-- les lignes orphelines ont été supprimées
ALTER TABLE fus_folder_user ADD CONSTRAINT fk_fus_fol FOREIGN KEY (fus_id_folder) REFERENCES fol_folder(fol_id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- Alexandre, le 11 mars 2016
-- bve_folder_doc -(n,1)-> bve_folder
-- les lignes orphelines ont été supprimées
ALTER TABLE fdo_folder_doc ADD CONSTRAINT fk_fdo_fol FOREIGN KEY (fdo_id_folder) REFERENCES fol_folder(fol_id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- Alexandre, le 14 mars 2016
-- bve_folder_doc -(n,1)-> ifp_indexfiche_paperless
-- les lignes orphelines ont été supprimées
ALTER TABLE fdo_folder_doc ADD CONSTRAINT fk_fdo_ifp FOREIGN KEY (fdo_id_doc) REFERENCES ifp_indexfiche_paperless(ifp_id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- indexfiche_paperless -(n,1)-> bve_type
-- les lignes orphelines ont eu leur clé mise à null
ALTER TABLE ifp_indexfiche_paperless ADD CONSTRAINT fk_ifp_typ FOREIGN KEY (ifp_id_code_document) REFERENCES typ_type(typ_code)
ON UPDATE CASCADE;

-- Alexandre, le 25 mars 2016
-- vdm_annotations_dossier -(n,1)-> bve_folder
-- les lignes orphelines ont été supprimées
ALTER TABLE ado_annotations_dossier ADD CONSTRAINT fk_ado_fol FOREIGN KEY (ado_folder) REFERENCES fol_folder(fol_id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- Alexandre, le 25 mars 2016
-- vdm_annotations_dossier -(n,1)-> vdm_users
-- les lignes orphelines ont été supprimées
ALTER TABLE ado_annotations_dossier ADD CONSTRAINT fk_ado_usr FOREIGN KEY (ado_login) REFERENCES usr_users(usr_login)
ON UPDATE CASCADE;

-- Alexandre, le 25 mars 2016
-- vdm_annotations -(n,1)-> indexfiche_paperless
-- les lignes orphelines ont été supprimées
ALTER TABLE ano_annotations ADD CONSTRAINT fk_ano_ifp FOREIGN KEY (ano_fiche) REFERENCES ifp_indexfiche_paperless(ifp_id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- Alexandre, le 25 mars 2016
-- vdm_annotations -(n,1)-> vdm_users
-- les lignes orphelines ont été supprimées
ALTER TABLE ano_annotations ADD CONSTRAINT fk_ano_usr FOREIGN KEY (ano_login) REFERENCES usr_users(usr_login)
ON UPDATE CASCADE;

-- Alexandre, le 05 avril 2016
-- ref_ID_CODE_ETABLISSEMENT -(n,1)-> ref_ID_CODE_SOCIETE
-- les lignes orphelines ont été supprimées
ALTER TABLE rce_ref_code_etablissement ADD CONSTRAINT fk_rce_rcs FOREIGN KEY (rce_siren) REFERENCES rcs_ref_code_societe(rcs_siren)
ON UPDATE CASCADE;

-- indexfiche_paperless -(n,1)-> iin_idx_indiv
-- les lignes orphelines ont eu leur clé mise à null
/*ALTER TABLE ifp_indexfiche_paperless ADD CONSTRAINT fk_ifp_iin FOREIGN KEY (ifp_id_num_matricule,ifp_id_code_client) REFERENCES iin_idx_indiv(iin_id_num_matricule,iin_id_code_client)
ON UPDATE CASCADE;*/

-- bve_processus -(n,1)-> vdm_users
-- les lignes orphelines ont été supprimées
ALTER TABLE pro_processus ADD CONSTRAINT fk_pro_usr FOREIGN KEY (pro_id_user) REFERENCES usr_users(usr_login)
ON UPDATE CASCADE;

-- bve_processus_type -(n,1)-> bve_processus
-- les lignes orphelines ont été supprimées
ALTER TABLE pty_processus_type ADD CONSTRAINT fk_pty_pro FOREIGN KEY (pty_id_processus) REFERENCES pro_processus(pro_id)
ON UPDATE CASCADE ON DELETE CASCADE;

-- bve_processus_type -(n,1)-> bve_type
-- les lignes orphelines ont été supprimées
ALTER TABLE pty_processus_type ADD CONSTRAINT fk_pty_typ FOREIGN KEY (pty_type) REFERENCES typ_type(typ_code)
ON UPDATE CASCADE;

/*-------------------------*/

END$$
DELIMITER ;

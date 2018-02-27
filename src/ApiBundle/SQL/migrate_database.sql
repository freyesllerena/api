DROP PROCEDURE IF EXISTS to_adp5;
DELIMITER $$
CREATE PROCEDURE to_adp5(OUT err_id INT, OUT err_msg TEXT)
adp5:BEGIN
SET FOREIGN_KEY_CHECKS=0;
SET err_id=0;
SET err_msg='';

-- Alexandre, le 02 février 2016
-- Ajout d'une table temporaire dans laquelle on stocke :
--   - le nom de la table dans la nouvelle BDD,
--   - le nom de la table dans l'ancienne BDD,
--   - le nombre de colonnes à ignorer
-- Le but est d'avertir l'utilisateur si le nombre de colonnes entre la nouvelle et l'ancienne BDD est incorrect (cas de colonnes ajoutées dans l'ancienne bdd par exemple...).
-- Il se peut que certaines colonnes ne soient pas reprises dans la nouvelle table (dans ce cas elles sont ignorées - variable @ignored_cols).
-- On peut également ajoutées de nouvelles colonnes dans la table de la nouvelle BDD (dans ce cas on les déclare dans la variable @added_cols)
DROP TEMPORARY TABLE IF EXISTS tmp_colonnes_tables;
CREATE TEMPORARY TABLE tmp_colonnes_tables (
  `new` VARCHAR(50),
  `old` VARCHAR(50),
  `ignored` TINYINT,
  `added` TINYINT DEFAULT 0
);
SET @ignored_cols=0;
SET @added_cols=0;

-- Utilisation
/*
CALL to_adp5(@err_id,@err_msg);
SELECT @err_id,@err_msg;
SHOW WARNINGS;
*/

/*------------------------------------
Création table com_completude
complétude manuelle
Issue de bve_completude et bve_completude_alert
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_completude' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_completude est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_completude_alert' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_completude_alert est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation com_completude
INSERT INTO com_completude
SELECT
com.id_completude,
com.id_user,
com.libelle,
FALSE,
IF(cal.id_completude IS NULL, 0, 1),
cal.email,
cal.periode,
IF(cal.id_completude IS NULL, 1, 0),
'ALL_POP',
cal.notification,
com.date_creation,
com.date_modification FROM bvrh5_olddb.bve_completude com INNER JOIN (
  SELECT MAX(id_completude) AS id_completude, id_user, libelle FROM bvrh5_olddb.bve_completude GROUP BY id_user, libelle
  ) mco ON com.id_completude = mco.id_completude LEFT JOIN (
    SELECT email, periode, notification, id_completude
     FROM bvrh5_olddb.bve_completude_alert cal
      INNER JOIN (
        SELECT MAX(id_completude_alert) AS id_completude_alert FROM bvrh5_olddb.bve_completude_alert WHERE id_completude != 0 GROUP BY id_completude
      ) mca ON cal.id_completude_alert = mca.id_completude_alert
  ) cal ON com.id_completude = cal.id_completude;

-- maj des stats
ANALYZE TABLE com_completude;

-- On ignore 1 colonne
SET @ignored_cols=1;
SET @added_cols=4;
INSERT INTO tmp_colonnes_tables VALUES
('com_completude', 'bve_completude', @ignored_cols, @added_cols);
SET @added_cols=0;

/*
SHOW INDEX FROM com_completude;
SELECT MAX(LENGTH(com_id_user)) FROM com_completude;
SELECT * FROM com_completude PROCEDURE ANALYSE();
SELECT * FROM bvrh5_olddb.bve_completude;
*/
/*--------------------------------------*/


/*------------------------------------
Création table cty_completude_type
Complétude manuelle
Issue de bve_completude_type
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_completude_type' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_completude_type est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO cty_completude_type SELECT NULL,`id_completude`, `type`, `date_creation`, `date_creation` FROM bvrh5_olddb.bve_completude_type;
-- maj des stats
ANALYZE TABLE cty_completude_type;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('cty_completude_type', 'bve_completude_type', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table con_confi
Issue de bve_config + vdm_cfg
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_config' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_config est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_cfg' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_cfg est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Variables de configuration spécifiques au client (Type Abonnement, Code PAC, Options)';
-- Alimentation à partir de bve_config
INSERT INTO con_config SELECT
NULL,
CASE `variable`
WHEN 'acces_annotation' THEN 'right_annotation_doc'
WHEN 'modification_value_after_import' THEN 'right_recherche_doc'
WHEN 'acces_stats' THEN 'access_statistiques'
WHEN 'pdf_to_excel' THEN 'access_export_pdf_excel'
WHEN 'import_masse' THEN 'access_import_masse'
WHEN 'boite_archive_active' THEN 'access_boite_archive'
ELSE variable
END,
CASE `variable`
WHEN 'modification_value_after_import' THEN IF(`valeur`='Y', 3, 1)
WHEN 'acces_annotation' THEN IF(`valeur`='Y', 7, 0)
ELSE
CASE `valeur` WHEN 'Y' THEN 1 WHEN 'N' THEN 0 ELSE `valeur` END
END,
'',
`date`,
`date`
FROM bvrh5_olddb.bve_config
WHERE variable IN (
'type_abo_visu',
'pac',
'acces_annotation',
'modification_value_after_import',
'acces_stats',
'multi_pac',
'pdf_to_excel',
'import_masse',
'boite_archive_active'
);
-- On crée le droit right_annoter_dossier à partir de right_annoter_doc
INSERT INTO con_config SELECT
NULL,
'right_annotation_dossier',
`con_valeur`,
'',
NOW(),
NOW()
FROM con_config WHERE con_variable = 'right_annotation_doc';
-- On crée le droit right_utilisateurs
INSERT INTO con_config VALUES (
NULL,
'right_utilisateurs',
CASE
WHEN
(SELECT `valeur` FROM bvrh5_olddb.bve_config WHERE `variable`='type_abo_visu'
)>1
THEN 3
ELSE 1
END,
'Gestion des droits',
NOW(),
NOW()
),
(NULL, 'version', '5.0', 'Version', NOW(), NOW());
-- Alimentation à partir de vdm_cfg
INSERT
IGNORE
INTO con_config SELECT
NULL,
CASE `variable`
WHEN 'max_export' THEN 'export_synchrone_documents_limit'
ELSE variable
END,
`valeur`,
`label`,
`date`,
`date`
FROM bvrh5_olddb.vdm_cfg
WHERE variable NOT IN (
'AUTOCOMPLETE_LOGIN',
'AUTOCOMPLETE_FORMULAIRE',
'AUTOCOMPLETE_USER',
'AUTOCOMPLETE_STATS',
'AUTOCOMPLETE_LISTEINDEX',
'AUTOCOMPLETE_LLK',
'AUTOCOMPLETE_MAIL',
'HAUTEUR_LISTE_INDEX',
'LARGEUR_LISTE_INDEX',
'STYLE',
'META_TAG_DESCRIPTION',
'YUI_VERSION',
'LANGUE_0',
'LANGUE_1',
'LANGUE_DEFAUT',
'CREATEUR',
'CREATION_INSTANCE',
'DATE',
'DATE_CREATION'
);
-- maj des stats
ANALYZE TABLE con_config;

-- On ignore 0 colonnes
SET @ignored_cols=0;
-- On ajoute la colonne `con_id`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('con_config', 'bve_config', @ignored_cols, @added_cols);
SET @added_cols=0;
/*
SELECT * FROM con_config PROCEDURE ANALYSE();
SELECT MAX(length(con_valeur)) FROM con_config;
SELECT * FROM bvrh5_olddb.bve_config where variable like 'type%';
SELECT * FROM bvrh5_olddb.vdm_cfg where variable like 'type%';
*/
/*--------------------------------------*/


/*------------------------------------
Création table cdv_cycle_de_vie
Issue de bve_cycle_de_vie
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_cycle_de_vie' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_cycle_de_vie est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO cdv_cycle_de_vie SELECT `id`, LPAD(`yyss`, 4, 0), `nb_doc_indiv`, `nb_doc_collect`, `date_upd`, `date_upd` FROM bvrh5_olddb.bve_cycle_de_vie;
-- maj des stats
ANALYZE TABLE cdv_cycle_de_vie;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('cdv_cycle_de_vie', 'bve_cycle_de_vie', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table err_erreur
REMARQUE : à conserver ?
Issue de bve_erreur
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_erreur' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_erreur est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO err_erreur SELECT `enreg`, `code`, `message`, `server`, `date`, `date` FROM bvrh5_olddb.bve_erreur;
-- maj des stats
ANALYZE TABLE err_erreur;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('err_erreur', 'bve_erreur', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table fol_folder
Issue de bve_folder
-------------------------------------*/
-- Test existance bve_folder
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_folder' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_folder est absente\r\n');
LEAVE adp5;
END IF;
-- Test existance vdm_baskets
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_baskets' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_baskets est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
-- Alexandre, le 11 mars 2016 : Ajout du propriétaire
INSERT INTO fol_folder
 SELECT
  fol.`id_folder`,
  fol.`libelle`,
  fus.`id_user`,
  'FOL',
  (SELECT COUNT(*) FROM bvrh5_olddb.bve_folder_doc fdo WHERE fdo.`id_folder` = fol.`id_folder`),
  fol.`date_creation`,
  fol.`date_modification`
 FROM bvrh5_olddb.bve_folder fol
 INNER JOIN
  bvrh5_olddb.bve_folder_user fus ON fol.id_folder=fus.id_folder;
-- Alexandre, le 19 avril 2016
-- Ré-intégration de vdm_baskets dans fol_folder
INSERT INTO fol_folder
SELECT NULL, 'Panier', bas.user_login, 'BAS', bas.nb_doc, NOW(), NOW() FROM
(SELECT bas.user_login, COUNT(*) AS nb_doc FROM bvrh5_olddb.`vdm_baskets` bas INNER JOIN bvrh5_olddb.`indexfiche_paperless` ifp ON bas.`fiche` = ifp.enreg GROUP BY user_login) bas;
-- maj des stats
ANALYZE TABLE fol_folder;

-- On ignore 2 colonnes
SET @ignored_cols=2;
-- On ajoute les colonnes `fol_id_owner` et `fol_type`
SET @added_cols=3;
INSERT INTO tmp_colonnes_tables VALUES
('fol_folder', 'bve_folder', @ignored_cols, @added_cols);
SET @added_cols=0;
/*
SELECT * FROM fol_folder;
*/
/*--------------------------------------*/


/*------------------------------------
Création table fdo_folder_doc
Issue de bve_folder_doc
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_folder_doc' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_folder_doc est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO fdo_folder_doc SELECT NULL, `id_doc`, `id_folder`, `date_creation`, `date_creation` FROM bvrh5_olddb.bve_folder_doc;
-- Alexandre, le 19 avril 2016
-- Ajout de vdm_baskets dans fdo_folder_doc
INSERT INTO fdo_folder_doc SELECT
NULL,
bas.`fiche`,
fol.`fol_id`,
NOW(),
NOW() FROM bvrh5_olddb.`vdm_baskets` bas INNER JOIN fol_folder fol ON fol.`fol_id_owner` = bas.`user_login` AND fol.`fol_type` = 'BAS';
-- maj des stats
ANALYZE TABLE fdo_folder_doc;

-- On ignore 2 colonnes
SET @ignored_cols=2;
-- On ajoute la colonne `fdo_id_folder`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('fdo_folder_doc', 'bve_folder_doc', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table fus_folder_user
Issue de bve_folder_user
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_folder_user' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_folder_user est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO fus_folder_user
SELECT NULL, `id_folder`, `id_user`, `date_acces`, NOW(), NOW() FROM bvrh5_olddb.bve_folder_user;
-- maj des stats
ANALYZE TABLE fus_folder_user;

-- On ignore 1 colonne
SET @ignored_cols=1;
-- On ajoute la colonne `fus_id`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('fus_folder_user', 'bve_folder_user', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table pro_profil
Issue de bve_profil
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_profil' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_profil est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS pro_profil;
CREATE TABLE `pro_profil` (
  `pro_id` INT NOT NULL AUTO_INCREMENT,
  `pro_libelle` VARCHAR(100) NOT NULL,
  `pro_order` BOOL NOT NULL,
  `pro_import` BOOL NOT NULL,
  `pro_generic` BOOL DEFAULT FALSE,
  `pro_adp` BOOL DEFAULT FALSE,
  `pro_arc` BOOL DEFAULT FALSE,
  `pro_created_at` DATETIME,
  `pro_updated_at` DATETIME,
  PRIMARY KEY (`pro_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT="Définition du périmètre utilisateur";
-- Alimentation
INSERT INTO pro_profil SELECT
`id_profil`,
`libelle`,
IF(UCASE(`order`)='Y',TRUE,FALSE),
IF(UCASE(`import`)='Y',TRUE,FALSE),
IF(UCASE(`generic`)='Y',TRUE,FALSE),
IF(UCASE(`adp`)='Y',TRUE,FALSE),
FALSE,
NOW(),
NOW()
FROM bvrh5_olddb.bve_profil;
-- maj des stats
ANALYZE TABLE pro_profil;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('pro_profil', 'bve_profil', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table pus_profil_user
Issue de bve_profil_user
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_profil_user' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_profil_user est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO pus_profil_user SELECT
NULL,
`id_profil`,
`id_user`,
NOW(),
NOW()
FROM bvrh5_olddb.bve_profil_user;
-- maj des stats
ANALYZE TABLE pus_profil_user;

-- On ignore 1 colonne
SET @ignored_cols=1;
-- On ajoute la colonne `pus_id`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('pus_profil_user', 'bve_profil_user', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table pde_profil_def
Issue de bve_profil_def
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_profil_def' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_profil_def est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS pde_profil_def;
CREATE TABLE `pde_profil_def` (
  `pde_id` INT NOT NULL,
  `pde_id_profil_def` INT NOT NULL,
  `pde_type` VARCHAR(10) NOT NULL,
  `pde_created_at` DATETIME,
  `pde_updated_at` DATETIME,
  PRIMARY KEY (`pde_id`,`pde_id_profil_def`,`pde_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Définition du périmètre et de la population 1 ligne = 1 habilitation, pas de ligne = pas de droit';
-- Alimentation
INSERT INTO pde_profil_def SELECT `id_profil`, `id_profil_def`, `type`, `date`, `date` FROM bvrh5_olddb.bve_profil_def;
-- maj des stats
ANALYZE TABLE pde_profil_def;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('pde_profil_def', 'bve_profil_def', @ignored_cols, @added_cols);
/*
SELECT * FROM pde_profil_def;
*/
/*--------------------------------------*/


/*------------------------------------
Création table pda_profil_def_appli
Issue de bve_profil_def_appli
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_profil_def_appli' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_profil_def_appli est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO pda_profil_def_appli SELECT `id_profil_appli`, `libelle`, `ref_bve`, `nbi`, `nbc`, IF(UCASE(`adp`)='Y',TRUE,FALSE), NOW(), NOW() FROM bvrh5_olddb.bve_profil_def_appli;
ANALYZE TABLE pda_profil_def_appli;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('pda_profil_def_appli', 'bve_profil_def_appli', @ignored_cols, @added_cols);
/*--------------------------------------*/
/* Optim index
show index from pda_profil_def_appli;
select pda_id_profil_appli,COUNT(*) nb from pda_profil_def_appli group by pda_id_profil_appli order by 2 desc;
select pda_libelle,COUNT(*) nb from pda_profil_def_appli group by pda_libelle order by 2 desc;
select pda_adp,COUNT(*) nb from pda_profil_def_appli group by pda_adp order by 2 desc;
*/
/*--------------------------------------*/

/*------------------------------------
Création table pdh_profil_def_habi
Issue de bve_profil_def_habi
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_profil_def_habi' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_profil_def_habi est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
INSERT INTO pdh_profil_def_habi SELECT `id_profil_habi`, `libelle`, `habilitation_i`, `habilitation_c`, `mode`, IF(UCASE(`adp`)='Y',TRUE,FALSE), NOW(), NOW() FROM bvrh5_olddb.bve_profil_def_habi;
ANALYZE TABLE pdh_profil_def_habi;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('pdh_profil_def_habi', 'bve_profil_def_habi', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table typ_type
Issue de bve_type
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_type' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_type est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
-- Alimentation
-- Alexandre, le 11/02/2016 : Lorsque id_domaine3 est vide, on récupère id_domaine2 si non vide. Si vide, on récupère id_domaine1.
INSERT INTO typ_type SELECT `code`, `code_adp`, IF(`id_domaine3` != '', `id_domaine3`, IF (`id_domaine2` != '', `id_domaine2`, `id_domaine1`)), `type`,
IF(UCASE(`individuel`)='O',TRUE,FALSE),
`vie_duree`, `num_ordre_1`, `num_ordre_2`, `num_ordre_3`, `num_ordre_4`, `date_effet`, NOW(), NOW() FROM bvrh5_olddb.bve_type;
-- maj des stats
ANALYZE TABLE typ_type;

-- On ignore 3 colonnes
SET @ignored_cols=3;
INSERT INTO tmp_colonnes_tables VALUES
('typ_type', 'bve_type', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table iin_idx_indiv
Issue de iin_idx_indiv
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='idx_indiv' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table idx_indiv est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
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
  PRIMARY KEY (`iin_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tous les salariés des clients DOCAPOST';
-- Alimentation
INSERT INTO iin_idx_indiv SELECT
`enreg`,
`ID_CODE_CLIENT`,
`ID_CODE_SOCIETE`,
`ID_CODE_JALON`,
`ID_CODE_ETABLISSEMENT`,
`ID_LIB_ETABLISSEMENT`,
`ID_NOM_SOCIETE`,
`ID_NOM_CLIENT`,
IF(TRIM(`ID_TYPE_PAIE`)='',NULL,`ID_TYPE_PAIE`),
`ID_PERIODE_PAIE`,
`ID_NOM_SALARIE`,
`ID_PRENOM_SALARIE`,
IF(TRIM(`ID_NOM_JEUNE_FILLE_SALARIE`)='',NULL,`ID_NOM_JEUNE_FILLE_SALARIE`),
IF(`ID_DATE_ENTREE`='0000-00-00 00:00:00', NULL,`ID_DATE_ENTREE`),
IF(`ID_DATE_SORTIE`='0000-00-00 00:00:00', NULL,`ID_DATE_SORTIE`),
`ID_NUM_NIR`,
`ID_NUM_MATRICULE`,
`fichier_index`,
`ID_CODE_CATEG_PROFESSIONNELLE`,
`ID_CODE_CATEG_SOCIO_PROF`,
`ID_TYPE_CONTRAT`,
`ID_AFFECTATION1`,
`ID_AFFECTATION2`,
`ID_AFFECTATION3`,
`ID_NUM_SIREN`,
`ID_NUM_SIRET`,
`ID_DATE_NAISSANCE`,
IF(TRIM(`ID_LIBRE1`)='',NULL,`ID_LIBRE1`),
IF(TRIM(`ID_LIBRE2`)='',NULL,`ID_LIBRE2`),
IF(TRIM(`ID_NUM_MATRICULE_GROUPE`)='',NULL,`ID_NUM_MATRICULE_GROUPE`),
IF(TRIM(`ID_NUM_MATRICULE_RH`)!='',`ID_NUM_MATRICULE_RH`,`ID_NUM_MATRICULE`),
`ID_CODE_ACTIVITE`,
/*`ID_CODE_CHRONO`,*/NULL,
`ID_DATE_1`,
`ID_DATE_2`,
`ID_DATE_3`,
`ID_DATE_4`,
`ID_DATE_5`,
`ID_DATE_6`,
`ID_DATE_7`,
`ID_DATE_8`,
`ID_DATE_ADP_1`,
`ID_DATE_ADP_2`,
`ID_ALPHANUM_1`,
`ID_ALPHANUM_2`,
`ID_ALPHANUM_3`,
`ID_ALPHANUM_4`,
`ID_ALPHANUM_5`,
`ID_ALPHANUM_6`,
`ID_ALPHANUM_7`,
`ID_ALPHANUM_8`,
`ID_ALPHANUM_9`,
`ID_ALPHANUM_10`,
`ID_ALPHANUM_11`,
`ID_ALPHANUM_12`,
`ID_ALPHANUM_13`,
`ID_ALPHANUM_14`,
`ID_ALPHANUM_15`,
`ID_ALPHANUM_16`,
`ID_ALPHANUM_17`,
`ID_ALPHANUM_18`,
`ID_ALPHANUM_ADP_1`,
`ID_ALPHANUM_ADP_2`,
`ID_NUM_1`,
`ID_NUM_2`,
`ID_NUM_3`,
`ID_NUM_4`,
`ID_NUM_5`,
`ID_NUM_6`,
`ID_NUM_7`,
`ID_NUM_8`,
`ID_NUM_9`,
`ID_NUM_10`,
`ID_NUM_ORDRE`,
NOW(),
NOW()
FROM bvrh5_olddb.idx_indiv;
-- Création des index sur la table iin_idx_indiv
-- Alexandre, le 14 avril 2016
ALTER TABLE iin_idx_indiv
  ADD UNIQUE KEY `iin_id_num_matricule` (`iin_id_num_matricule`,`iin_id_code_client`),
  ADD KEY `iin_id_nom_salarie` (`iin_id_nom_salarie`),
  ADD KEY `iin_id_prenom_salarie` (`iin_id_prenom_salarie`), -- recherche que par prénom ? sinon créer clé composée (nom,prenom)
--   ADD KEY `iin_id_type_contrat` (`iin_id_type_contrat`), -- cardinalité 6
  ADD KEY `iin_id_code_categ_socio_prof` (`iin_id_code_categ_socio_prof`),
  ADD KEY `iin_id_code_categ_professionnelle` (`iin_id_code_categ_professionnelle`),
  ADD KEY `iin_id_code_etablissement` (`iin_id_code_etablissement`),
--  ADD KEY `iin_id_periode_paie` (`iin_id_periode_paie`), -- cardinalité 6
  ADD KEY `iin_id_nom_jeune_fille_salarie` (`iin_id_nom_jeune_fille_salarie`),
  ADD KEY `iin_id_date_entree` (`iin_id_date_entree`),
  ADD KEY `iin_id_date_sortie` (`iin_id_date_sortie`),
  ADD KEY `iin_id_num_nir` (`iin_id_num_nir`),
  ADD KEY `iin_id_lib_etablissement` (`iin_id_lib_etablissement`),
--  ADD KEY `iin_id_code_societe` (`iin_id_code_societe`), -- cardinalité 6
  ADD KEY `iin_id_nom_societe` (`iin_id_nom_societe`),
  ADD KEY `iin_id_date_naissance` (`iin_id_date_naissance`),
--  ADD KEY `iin_id_num_siren` (`iin_id_num_siren`), -- cardinalité 6
  ADD KEY `iin_id_num_siret` (`iin_id_num_siret`),
  ADD KEY `iin_id_num_matricule_rh` (`iin_id_num_matricule_rh`);

-- maj des stats
ANALYZE TABLE iin_idx_indiv;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('iin_idx_indiv', 'idx_indiv', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table ifp_indexfiche_paperless
Issu de indexfiche_paperless
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='indexfiche_paperless' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table indexfiche_paperless est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS ifp_indexfiche_paperless;
CREATE TABLE `ifp_indexfiche_paperless` (
  `ifp_id` INT NOT NULL AUTO_INCREMENT,
  `ifp_documentsassocies` VARCHAR(40),
  `ifp_vdm_localisation` TEXT NOT NULL,
  `ifp_refpapier` VARCHAR(4) DEFAULT NULL,
  `ifp_nombrepages` SMALLINT DEFAULT NULL,
  `ifp_id_code_chrono` VARCHAR(255) DEFAULT NULL,
  `ifp_id_numero_boite_archive` VARCHAR(255) DEFAULT NULL,
  `ifp_interbox` BOOL NOT NULL DEFAULT 0,
  `ifp_lot_index` VARCHAR(100) DEFAULT NULL,
  `ifp_lot_production` SMALLINT NOT NULL,
  `ifp_id_nom_societe` VARCHAR(255) DEFAULT NULL,
  `ifp_id_company` VARCHAR(255) DEFAULT NULL,
  `ifp_id_nom_client` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_client` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_societe` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_etablissement` VARCHAR(255) DEFAULT NULL,
  `ifp_id_lib_etablissement` VARCHAR(255) DEFAULT NULL,
  `ifp_id_code_jalon` VARCHAR(255) DEFAULT NULL,
  `ifp_id_libelle_jalon` VARCHAR(255) DEFAULT NULL,
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
  `ifp_id_periode_paie` VARCHAR(6) DEFAULT NULL,
  `ifp_id_periode_exercice_sociale` VARCHAR(4) DEFAULT NULL,
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
  `ifp_id_num_matricule_groupe` VARCHAR(255) DEFAULT NULL,
  `ifp_id_annotation` TEXT NOT NULL,
  `ifp_id_conteneur` VARCHAR(255) NOT NULL,
  `ifp_id_boite` VARCHAR(255) NOT NULL,
  `ifp_id_lot` VARCHAR(255) NOT NULL,
  `ifp_id_num_ordre` VARCHAR(2) DEFAULT NULL,
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
  PRIMARY KEY (`ifp_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Toutes les infos portant sur les documents transmis et archivés';


-- Alimentation 18 sec
INSERT INTO ifp_indexfiche_paperless SELECT
`enreg`,
`documentsassocies`,
`vdm_localisation`,
`refpapier`,
`nombrepages`,
`ID_CODE_CHRONO`,
`ID_NUMERO_BOITE_ARCHIVE`,
IF(UCASE(`interbox`)='Y',TRUE,FALSE),
IF(TRIM(`lot_index`), NULL, `lot_index`),
`lot_production`,
`id_nom_societe`,
`id_company`,
`id_nom_client`,
`id_code_client`,
`id_code_societe`,
`id_code_etablissement`,
`id_lib_etablissement`,
`id_code_jalon`,
IF(TRIM(`id_libelle_jalon`), NULL, `id_libelle_jalon`),
`id_num_siren`,
`id_num_siret`,
`id_indice_classement`,
`id_unique_document`,
`id_type_document`,
`id_libelle_document`,
`id_code_document`,
`id_format_document`,
`id_auteur_document`,
`id_source_document`,
`id_num_version_document`,
`id_poids_document`,
`id_nbr_pages_document`,
`id_profil_archivage`,
`id_etat_archive`,
IF(TRIM(`id_periode_paie`)='', NULL,
  IF(LENGTH(`id_periode_paie`)!=6, NULL,
    CASE WHEN SUBSTR(`id_periode_paie`, 1, 4)>1900 AND SUBSTR(`id_periode_paie`, 1, 4)<2100 AND
      SUBSTR(`id_periode_paie`, 5)>=01 AND SUBSTR(`id_periode_paie`, 5)<=12 THEN `id_periode_paie` ELSE NULL
    END
  )
),
IF(TRIM(`id_periode_exercice_sociale`)='', NULL,`id_periode_exercice_sociale`),
`id_date_dernier_acces_document`,
`id_date_archivage_document`,
`id_duree_archivage_document`,
`id_date_fin_archivage_document`,
`id_nom_salarie`,
`id_prenom_salarie`,
`id_nom_jeune_fille_salarie`,
`id_date_naissance_salarie`,
IF(`id_date_entree`='0000-00-00 00:00:00', NULL, `id_date_entree`),
IF(`id_date_sortie`='0000-00-00 00:00:00', NULL, `id_date_sortie`),
`id_num_nir`,
`id_code_categ_professionnelle`,
`id_code_categ_socio_prof`,
`id_type_contrat`,
`id_affectation1`,
`id_affectation2`,
`id_affectation3`,
`id_libre1`,
`id_libre2`,
`id_affectation1_date`,
`id_affectation2_date`,
`id_affectation3_date`,
`id_libre1_date`,
`id_libre2_date`,
`id_num_matricule`,
IF(typ.individuel != 1 OR TRIM(id_num_matricule_rh) !='', id_num_matricule_rh, id_num_matricule),
`id_num_matricule_groupe`,
`id_annotation`,
`id_conteneur`,
`id_boite`,
`id_lot`,
IF(TRIM(`id_num_ordre`)='', NULL, id_num_ordre),
`archive_cfec`,
`archive_serialnumber`,
`archive_datetime`,
`archive_name`,
`archive_cfe`,
`numeropdf`,
`opn_provenance`,
`status_num`,
IF(UCASE(`is_doublon`)='Y',TRUE,FALSE),
`login`,
`modedt`,
`numdtr`,
`old_id_date_dernier_acces_document`,
`old_id_date_archivage_document`,
`old_id_date_fin_archivage_document`,
`old_id_date_naissance_salarie`,
`old_id_date_entree`,
`old_id_date_sortie`,
`id_code_activite`,
IF(UCASE(`cycle_fin_de_vie`)='Y',TRUE,FALSE),
`cycle_purger`,
IF(UCASE(`geler`)='Y',TRUE,FALSE),
`id_date_1`,
`id_date_2`,
`id_date_3`,
`id_date_4`,
`id_date_5`,
`id_date_6`,
`id_date_7`,
`id_date_8`,
`id_date_adp_1`,
`id_date_adp_2`,
`id_alphanum_1`,
`id_alphanum_2`,
`id_alphanum_3`,
`id_alphanum_4`,
`id_alphanum_5`,
`id_alphanum_6`,
`id_alphanum_7`,
`id_alphanum_8`,
`id_alphanum_9`,
`id_alphanum_10`,
`id_alphanum_11`,
`id_alphanum_12`,
`id_alphanum_13`,
`id_alphanum_14`,
`id_alphanum_15`,
`id_alphanum_16`,
`id_alphanum_17`,
`id_alphanum_18`,
`id_alphanum_adp_1`,
`id_alphanum_adp_2`,
`id_num_1`,
`id_num_2`,
`id_num_3`,
`id_num_4`,
`id_num_5`,
`id_num_6`,
`id_num_7`,
`id_num_8`,
`id_num_9`,
`id_num_10`,
`cycle_temps_parcouru`,
`cycle_temps_restant`,
IF(UCASE(`set_fin_archivage`)='Y',TRUE,FALSE),
FALSE,
NOW(),
NOW()
FROM bvrh5_olddb.indexfiche_paperless ifp LEFT JOIN bvrh5_olddb.bve_type typ ON ifp.id_code_document=typ.code WHERE status_num <> 'REJET' OR status_num = 'REJET' AND id_date_archivage_document >= STR_TO_DATE('15/07/2014', '%d/%m/%Y');
-- Création des index sur la table ifp_indexfiche_paperless
-- Alexandre, le 14 avril 2016
ALTER TABLE ifp_indexfiche_paperless
  ADD KEY `ifp_nombrepages` (`ifp_nombrepages`),
-- modif C.Reboul le 03/12/2015
  ADD KEY `ifp_id_code_document_societe_jalon` (`ifp_id_code_document`,`ifp_id_code_societe`,`ifp_id_code_jalon`),
-- Ajout CR le 03/12/2015
  ADD KEY ifp_mat_cli_doc (`ifp_id_num_matricule`,`ifp_id_code_client`,`ifp_id_code_document`),
  ADD KEY `ifp_id_indice_classement` (`ifp_id_indice_classement`),
  ADD KEY `ifp_id_unique_document` (`ifp_id_unique_document`),
  ADD KEY `ifp_id_type_document` (`ifp_id_type_document`),
  ADD KEY `ifp_id_poids_document` (`ifp_id_poids_document`),
  ADD KEY `ifp_id_nbr_pages_document` (`ifp_id_nbr_pages_document`),
  ADD KEY `ifp_id_periode_paie` (`ifp_id_periode_paie`),
  ADD KEY `ifp_id_periode_exercice_sociale` (`ifp_id_periode_exercice_sociale`),
  ADD KEY `ifp_id_date_dernier_acces_document` (`ifp_id_date_dernier_acces_document`),
  ADD KEY `ifp_id_date_archivage_document` (`ifp_id_date_archivage_document`),
  ADD KEY `ifp_id_date_fin_archivage_document` (`ifp_id_date_fin_archivage_document`),
  ADD KEY `ifp_id_nom_salarie` (`ifp_id_nom_salarie`),
  ADD KEY `ifp_id_prenom_salarie` (`ifp_id_prenom_salarie`),
  ADD KEY `ifp_id_nom_jeune_fille_salarie` (`ifp_id_nom_jeune_fille_salarie`),
  ADD KEY `ifp_id_date_entree` (`ifp_id_date_entree`),
  ADD KEY `ifp_id_date_sortie` (`ifp_id_date_sortie`),
  ADD KEY `ifp_id_num_nir` (`ifp_id_num_nir`),
  ADD KEY `ifp_id_code_categ_professionnelle` (`ifp_id_code_categ_professionnelle`),
  ADD KEY `ifp_id_libre1` (`ifp_id_libre1`),
  ADD KEY `ifp_id_libre1_date` (`ifp_id_libre1_date`),
  ADD KEY `ifp_modedt` (`ifp_modedt`),
  ADD KEY `ifp_numdtr` (`ifp_numdtr`),
  ADD KEY `ifp_id_libelle_doc_code_doc` (`ifp_id_libelle_document`,`ifp_id_code_document`),
  ADD KEY `ifp_id_numero_boite_archive` (`ifp_id_numero_boite_archive`),
  ADD KEY `ifp_id_code_activite` (`ifp_id_code_activite`),
  ADD KEY `ifp_id_num_matricule_rh` (`ifp_id_num_matricule_rh`);
-- maj des stats
ANALYZE TABLE ifp_indexfiche_paperless;

-- On ignore 0 colonnes
SET @ignored_cols=0;
-- On ajoute la colonne `ifp_is_personnel`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('ifp_indexfiche_paperless', 'indexfiche_paperless', @ignored_cols, @added_cols);
SET @added_cols=0;

/*
SHOW INDEX FROM ifp_indexfiche_paperless;
SHOW INDEX FROM bvrh5_olddb.indexfiche_paperless;
*/
/*--------------------------------------*/


/*------------------------------------
Création table ian_idx_anomalies
Issu de idx_anomalies
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='idx_anomalies' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table idx_anomalies est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS ian_idx_anomalies;
CREATE TABLE `ian_idx_anomalies` (
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
-- Alimentation
INSERT INTO ian_idx_anomalies SELECT
NULL,
`anomalie`,
`date`,
IF(TRIM(`ref_adp`)!='', `ref_adp`, NULL),
IF(TRIM(`type`)!='', `type`, NULL),
IF(TRIM(`matricule`)!='', `matricule`, NULL),
IF(TRIM(`id_etat_archive`)!='', `id_etat_archive`, NULL),
`fichier`,
IF(`indiv_coll`='COLLECTIF', FALSE, TRUE),
IF(TRIM(`nom_prenom`)!='', `nom_prenom`, NULL),
NOW(),
NOW()
FROM bvrh5_olddb.idx_anomalies;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE ian_idx_anomalies;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('ian_idx_anomalies', 'idx_anomalies', @ignored_cols, @added_cols);
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
ref_ID_CODE_JALON
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_AFFECTATION1' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_AFFECTATION1 est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_AFFECTATION2' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_AFFECTATION2 est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_AFFECTATION3' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_AFFECTATION3 est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_ACTIVITE' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_ACTIVITE est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_CATEG_PROFESSIONNELLE' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_CATEG_PROFESSIONNELLE est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_CATEG_SOCIO_PROF' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_CATEG_SOCIO_PROF est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_CLIENT' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_CLIENT est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_DOCUMENT' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_DOCUMENT est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_LIBRE1' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_LIBRE1 est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_LIBRE2' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_LIBRE2 est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_TYPE_CONTRAT' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_TYPE_CONTRAT est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_JALON' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_JALON est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS rid_ref_id;
CREATE TABLE `rid_ref_id` (
  `rid_id` INT NOT NULL AUTO_INCREMENT,
  `rid_code` VARCHAR(50) NOT NULL,
  `rid_libelle` VARCHAR(255) NOT NULL,
  `rid_id_code_client` VARCHAR(20) NOT NULL,
  `rid_type` VARCHAR(30) NOT NULL,
  `rid_created_at` DATETIME,
  `rid_updated_at` DATETIME,
  PRIMARY KEY (`rid_id`),
  UNIQUE INDEX `rid_code_cli_typ` (`rid_code`,`rid_id_code_client`,`rid_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Les ref_ID sont les flux remplis par le client (filtres)';
-- Alimentation
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdAffectation1', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_AFFECTATION1 WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdAffectation2', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_AFFECTATION2 WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdAffectation3', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_AFFECTATION3 WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdCodeActivite', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_CODE_ACTIVITE WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdCodeCategProfessionnelle', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_CODE_CATEG_PROFESSIONNELLE WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdCodeCategSocioProf', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_CODE_CATEG_SOCIO_PROF WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'CodeClient', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_CODE_CLIENT WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, '', 'CodeDocument', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_CODE_DOCUMENT;
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdLibre1', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_LIBRE1 WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdLibre2', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_LIBRE2 WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdTypeContrat', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_TYPE_CONTRAT WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
INSERT IGNORE INTO rid_ref_id SELECT NULL, `valeur`, `libelle`, `ID_CODE_CLIENT`, 'IdCodeJalon', `Date`, `Date`
FROM bvrh5_olddb.ref_ID_CODE_JALON WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '';
/*--------------------------------------*/
ANALYZE TABLE rid_ref_id;
/*--------------------------------------*/


/*------------------------------------
Création table rcs_ref_code_societe
Issu de ref_ID_CODE_SOCIETE
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_SOCIETE' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_SOCIETE est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS rcs_ref_code_societe;
CREATE TABLE `rcs_ref_code_societe` (
  `rcs_id` INT NOT NULL AUTO_INCREMENT,
  `rcs_code` VARCHAR(50) NOT NULL,
  `rcs_libelle` VARCHAR(255) NOT NULL,
  `rcs_siren` VARCHAR(9) NOT NULL,
  `rcs_id_code_client` VARCHAR(20) NOT NULL,
  `rcs_created_at` DATETIME,
  `rcs_updated_at` DATETIME,
  PRIMARY KEY (`rcs_id`),
  UNIQUE KEY `rcs_code_cli` (`rcs_code`, `rcs_id_code_client`),
  KEY `rcs_id_code_client` (`rcs_id_code_client`),
  KEY `rcs_libelle` (`rcs_libelle`),
  UNIQUE KEY `rcs_siren` (`rcs_siren`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flux des sociétés remplis par le client';
-- Alimentation
INSERT IGNORE INTO rcs_ref_code_societe SELECT
NULL,
`valeur`,
`libelle`,
`ID_NUM_SIREN`,
`ID_CODE_CLIENT`,
`date`,
`date`
FROM bvrh5_olddb.ref_ID_CODE_SOCIETE WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '' AND TRIM(`ID_NUM_SIREN`) != '';
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE rcs_ref_code_societe;

-- On ignore 1 colonne
SET @ignored_cols=1;
-- On ajoute 1 colonne
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('rcs_ref_code_societe', 'ref_ID_CODE_SOCIETE', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table rce_ref_code_etablissement
Issu de ref_ID_CODE_ETABLISSEMENT
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='ref_ID_CODE_ETABLISSEMENT' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table ref_ID_CODE_ETABLISSEMENT est absente\r\n');
LEAVE adp5;
END IF;
/*-----------------*/
DROP TABLE IF EXISTS rce_ref_code_etablissement;
CREATE TABLE `rce_ref_code_etablissement` (
  `rce_id` INT NOT NULL AUTO_INCREMENT,
  `rce_code` VARCHAR(50) NOT NULL,
  `rce_libelle` VARCHAR(255) NOT NULL,
  `rce_nic` VARCHAR(5) NOT NULL,
  `rce_id_societe` INT NULL,
  `rce_created_at` DATETIME,
  `rce_updated_at` DATETIME,
  PRIMARY KEY (`rce_id`),
  UNIQUE KEY `rce_code_soc` (`rce_code`, `rce_id_societe`),
  KEY `rce_id_societe` (`rce_id_societe`),
  KEY `rce_libelle` (`rce_libelle`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flux des établissements remplis par le client';
-- Alimentation
INSERT IGNORE INTO rce_ref_code_etablissement SELECT
NULL,
`valeur`,
`libelle`,
SUBSTR(ID_NUM_SIRET, 10),
`rcs_id`,
`date`,
`date`
FROM bvrh5_olddb.ref_ID_CODE_ETABLISSEMENT LEFT JOIN rcs_ref_code_societe rcs ON SUBSTR(ID_NUM_SIRET, 1, 9) = rcs.rcs_siren
WHERE TRIM(`valeur`) != '' AND TRIM(`ID_CODE_CLIENT`) != '' AND TRIM(`ID_NUM_SIRET`) != '';
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE rce_ref_code_etablissement;

-- On ignore 1 colonne
SET @ignored_cols=2;
-- On ajoute 1 colonne
SET @added_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('rce_ref_code_etablissement', 'ref_ID_CODE_ETABLISSEMENT', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table aso_alerte_securite_opn
Issue de vdm_alerte_securite_opn
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_alerte_securite_opn' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_alerte_securite_opn est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO aso_alerte_securite_opn SELECT
`enreg`,
`referer`,
`machine`,
`request_method`,
`request_uri`,
TIMESTAMP(`date`,`heure`),
TIMESTAMP(`date`,`heure`)
FROM bvrh5_olddb.vdm_alerte_securite_opn;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE aso_alerte_securite_opn;

-- On ignore 2 colonnes
SET @ignored_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('aso_alerte_securite_opn', 'vdm_alerte_securite_opn', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table ano_annotations
Issue de vdm_annotations
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_annotations' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_annotations est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO ano_annotations SELECT `enreg`, `fiche`, `login`, `etat`, `statut`, `texte`, `date`, `date`
FROM bvrh5_olddb.vdm_annotations;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE ano_annotations;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('ano_annotations', 'vdm_annotations', @ignored_cols, @added_cols);
/*--------------------------------------*/

/*------------------------------------
Création table ado_annotations_dossier
Issue de vdm_annotations_dossier
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_annotations_dossier' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_annotations_dossier est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO ado_annotations_dossier SELECT `enreg`, `fiche`, `login`, `etat`, `statut`, `texte`, `date`, `date`
FROM bvrh5_olddb.vdm_annotations_dossier;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE ado_annotations_dossier;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('ado_annotations_dossier', 'vdm_annotations_dossier', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table edi_edition
Issue de vdm_edition
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_edition' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_edition est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO edi_edition SELECT NULL, `fiche`, `user_login`, `lastaction`, `lastaction` FROM bvrh5_olddb.vdm_edition;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE edi_edition;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('edi_edition', 'vdm_edition', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table fdp_fdp
Issue de vdm_fdp
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_fdp' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_fdp est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO fdp_fdp SELECT
`enreg`,
`nom`,
`id_application`,
`id_fdp`,
`chemin_on`,
`chemin_off`,
`x`,
`y`,
`scale_x`,
`scale_y`,
`rotation`,
`cgv`,
`as_chemin_on`,
`as_x`,
`as_y`,
`as_scale_x`,
`as_scale_y`,
`as_rotation`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_fdp;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE fdp_fdp;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('fdp_fdp', 'vdm_fdp', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table icf_info_cfec
Issue de vdm_info_cfec
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_info_cfec' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_info_cfec est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO icf_info_cfec SELECT
`enreg`,
`id_alias`,
`cfec_base_principal`,
`cfec_cert_principal`,
`cfec_numero_cfe_principal`,
`cfec_numero_cfec_principal`,
`cfec_base_secondaire`,
`cfec_cert_secondaire`,
`cfec_numero_cfec_secondaire`,
`cfec_numero_cfe_secondaire`,
`ci_url_principal`,
`ci_rep_transfer_principal`,
`ci_rep_cr_principal`,
`ci_url_secondaire`,
`ci_rep_transfer_secondaire`,
`ci_rep_cr_secondaire`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_info_cfec;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE icf_info_cfec;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('icf_info_cfec', 'vdm_info_cfec', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table iup_interupload
Issue de vdm_interupload
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_interupload' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_interupload est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO iup_interupload SELECT
  `enreg`,
  `ticket`,
  `challenge`,
  `statut`,
  `date_production`,
  `date_archivage`,
  `config`,
  `metadata_creation`,
  `metadata_production`,
  `metadata_archivage`,
  `date_creation`,
  `date_modification`
FROM bvrh5_olddb.vdm_interupload;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE iup_interupload;

-- On ignore 2 colonnes
SET @ignored_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('iup_interupload', 'vdm_interupload', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table iuc_interupload_cfg
Issue de vdm_interupload_cfg
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_interupload_cfg' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_interupload_cfg est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO iuc_interupload_cfg SELECT
`enreg`,
`id_interupload`,
`codeClient`,
`codeAppli`,
`param`,
`config`,
`script_archivage_specifique`,
`interuploadWeb`,
`versionApplet`,
`id_upload`,
`date_creation`,
`date_modification`
FROM bvrh5_olddb.vdm_interupload_cfg;
/*--------------------------------------*/
-- maj des stats
-- SELECT * FROM iuc_interupload_cfg;
ANALYZE TABLE iuc_interupload_cfg;

-- On ignore 2 colonnes
SET @ignored_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('iuc_interupload_cfg', 'vdm_interupload_cfg', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table la0_langue0
Issue de vdm_langue0
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_langue0' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_langue0 est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO la0_langue0 SELECT NULL, `variable`, `valeur`, `date`, `date` FROM bvrh5_olddb.vdm_langue0;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE la0_langue0;

-- On ignore 1 colonne
SET @ignored_cols=1;
-- On ajoute la colonne `la0_id`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('la0_langue0', 'vdm_langue0', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table la1_langue1
Issue de vdm_langue1
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_langue1' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_langue1 est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO la1_langue1 SELECT NULL, `variable`, `valeur`, `date`, `date` FROM bvrh5_olddb.vdm_langue1;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE la1_langue1;

-- On ignore 1 colonne
SET @ignored_cols=1;
-- On ajoute la colonne `la1_id`
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('la1_langue1', 'vdm_langue1', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table las_laserlike
Issue de vdm_laserlike
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_laserlike' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_laserlike est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO las_laserlike SELECT
`enreg`,
`debut`,
`fin`,
`chemin`,
`libelle`,
`id_application`,
`type`,
`cfec_base`,
`cfec_cert`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_laserlike;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE las_laserlike;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('las_laserlike', 'vdm_laserlike', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table las_laserlike
Issue de vdm_mails
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_mails' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_mails est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO mai_mails SELECT
`enreg`,
`user_login`,
`mail_from`,
`mail_to`,
`mail_copy`,
`mail_hidden_copy`,
IF(UCASE(`mail_notify`)='Y',TRUE,FALSE),
IF(UCASE(`mail_copy_to_sender`)='Y',TRUE,FALSE),
`mail_subject`,
`mail_message`,
`mail_date`,
`mail_time`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_mails;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE mai_mails;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('mai_mails', 'vdm_mails', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table prt_print
Issue de vdm_print
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_print' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_print est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO prt_print SELECT `enreg`, `user_login`, `fiche`, `id_session`, NOW(), NOW() FROM bvrh5_olddb.vdm_print;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE prt_print;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('prt_print', 'vdm_print', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table rap_rapport
Issue de rapport_import, trace_habilitation_masse
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='rapport_import' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table rapport_import est absente\r\n');
LEAVE adp5;
END IF;
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='trace_habilitation_masse' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table trace_habilitation_masse est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
DROP TABLE IF EXISTS rap_rapport;
CREATE TABLE `rap_rapport` (
	`rap_id` INT(11) NOT NULL AUTO_INCREMENT,
	`rap_id_user` VARCHAR(50) DEFAULT NULL,
	`rap_type_rapport` VARCHAR(100) NOT NULL,
	`rap_fichier` LONGTEXT DEFAULT NULL,
	`rap_libelle_fic` VARCHAR(255) DEFAULT NULL,
	`rap_etat` VARCHAR(2) NOT NULL,
	`rap_old_id` INT(11) DEFAULT NULL,
	`rap_created_at` DATETIME DEFAULT NULL,
	`rap_updated_at` DATETIME DEFAULT NULL,
	PRIMARY KEY (`rap_id`),
	INDEX `fk_rap_usr` (`rap_id_user`),
	CONSTRAINT `fk_rap_usr` FOREIGN KEY (`rap_id_user`) REFERENCES `usr_users` (`usr_login`) ON UPDATE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Table qui regroupe les rapports'
;
-- Alimentation
INSERT INTO rap_rapport SELECT
NULL,
NULL,
'IMPORT',
text_rapport,
IF(`etat`='KO', 'rejected.csv', NULL),
etat,
NULL,
date_creation,
date_creation
FROM bvrh5_olddb.rapport_import
;
INSERT INTO rap_rapport SELECT
NULL,
NULL,
'HABILITATION',
rapport,
'report_habilitation.csv',
IF(`erreur`=0,'OK','KO'),
enreg,
date,
date
FROM bvrh5_olddb.trace_habilitation_masse
;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE rap_rapport;

-- On ignore 1 colonne
SET @ignored_cols=1;
-- On ajoute 1 colonne
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('rap_rapport', 'rapport_import', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table iha_import_habilitation
Issue de trace_habilitation_masse
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='trace_habilitation_masse' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table trace_habilitation_masse est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
DROP TABLE IF EXISTS iha_import_habilitation;
CREATE TABLE `iha_import_habilitation` (
	`iha_id` INT(11) NOT NULL AUTO_INCREMENT,
	`iha_traite` SMALLINT NOT NULL DEFAULT 0,
	`iha_succes` SMALLINT NOT NULL DEFAULT 0,
	`iha_erreur` SMALLINT NOT NULL DEFAULT 0,
	`iha_id_rapport` INT NOT NULL,
	`iha_created_at` DATETIME NULL DEFAULT NULL,
	`iha_updated_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`iha_id`),
	INDEX `fk_iha_rap` (`iha_id_rapport`),
	CONSTRAINT `fk_iha_rap` FOREIGN KEY (`iha_id_rapport`)
	REFERENCES `rap_rapport` (`rap_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Logs des imports en masse des habilitations'
;
-- Alimentation
INSERT INTO iha_import_habilitation SELECT
NULL,
traite,
succes,
erreur,
rap_id,
date,
date
FROM bvrh5_olddb.trace_habilitation_masse INNER JOIN rap_rapport ON enreg = rap_old_id
;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE iha_import_habilitation;

-- On ignore 2 colonnes
SET @ignored_cols=2;
-- On ajoute 1 colonne
SET @added_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('iha_import_habilitation', 'trace_habilitation_masse', @ignored_cols, @added_cols);
SET @added_cols=0;
-- On supprime la colonne rap_old_id de la table rap_rapport
ALTER TABLE rap_rapport DROP rap_old_id;
/*--------------------------------------*/


/*------------------------------------
Création table ses_sessions_opn
Issue de vdm_sessions_opn
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_sessions_opn' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_sessions_opn est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO ses_sessions_opn SELECT
`enreg`,
`id_session`,
`user_login`,
`machine`,
`first_action`,
`last_action`,
`variables`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_sessions_opn;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE ses_sessions_opn;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('ses_sessions_opn', 'vdm_sessions_opn', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table sto_stockage
Issue de vdm_stockage
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_stockage' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_stockage est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO sto_stockage SELECT
`enreg`,
`volume`,
`libelle`,
`chemin`,
`code_client`,
`code_application`,
IF(UCASE(`use_vis`)='Y',TRUE,FALSE),
`type`,
`cfec_base`,
`cfec_cert`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_stockage;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE sto_stockage;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('sto_stockage', 'vdm_stockage', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table spr_suivi_prod
Issue de vdm_suivi_prod
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_suivi_prod' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_suivi_prod est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO spr_suivi_prod SELECT
`enreg`,
`fichier_dat`,
`numero_bdl`,
`chrono_odin`,
`banniere_debut`,
`banniere_fin`,
`code_client`,
`code_application`,
`type_operation`,
`chrono_client`,
`spool`,
`fichier_log_empreinte`,
`max_enreg_avant`,
`nombre_enregs_avant`,
`max_enreg_apres`,
`nombre_enregs_apres`,
`nombre_pages_hebergees_avant`,
`nombre_pages_hebergees_apres`,
`nombre_dossiers`,
`nombre_pages`,
`nombre_pages_vides`,
`premier_index`,
`dernier_index`,
`temps_pdf`,
`temps_indexation`,
`temps_injection`,
`temps_autre`,
`compteur_dossiers_theorique`,
`compteur_pages_theorique`,
`table_indexfiche`,
`audit`,
`lot_index`,
`lot_production`,
`code_client_appelant`,
`code_application_appelant`,
`numerobdl_appelant`,
`information_client`,
TIMESTAMP(`date`,`heure`),
TIMESTAMP(`date`,`heure`)
FROM bvrh5_olddb.vdm_suivi_prod;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE spr_suivi_prod;

-- On ignore 2 colonnes
SET @ignored_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('spr_suivi_prod', 'vdm_suivi_prod', @ignored_cols, @added_cols);
/*--------------------------------------*/



/*------------------------------------
Création table tam_tampons
Issue de vdm_tampons
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_tampons' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_tampons est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO tam_tampons SELECT
`enreg`,
`id_fdp`,
`chemin`,
`x`,
`y`,
`scale_x`,
`scale_y`,
`rotation`,
IF(UCASE(`mode_overlay`)='Y',TRUE,FALSE),
`add_free_text`,
`as_x`,
`as_y`,
`as_scale_x`,
`as_scale_y`,
`as_rotation`,
IF(UCASE(`as_mode_overlay`)='Y',TRUE,FALSE),
NOW(),
NOW()
FROM bvrh5_olddb.vdm_tampons;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE tam_tampons;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('tam_tampons', 'vdm_tampons', @ignored_cols, @added_cols);
/*--------------------------------------*/



/*------------------------------------
Création table tse_time_session
Issue de vdm_time_session
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_time_session' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_time_session est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO tse_time_session SELECT
`enreg`,
`id_session`,
`user_login`,
`first_action`,
`last_action`,
`time_session`,
`date`,
`date`
FROM bvrh5_olddb.vdm_time_session;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE tse_time_session;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('tse_time_session', 'vdm_time_session', @ignored_cols, @added_cols);
/*--------------------------------------*/



/*------------------------------------
Création table tcf_trace_cfec
Issue de vdm_trace_cfec
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_trace_cfec' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_trace_cfec est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO tcf_trace_cfec SELECT
`enreg`,
`nom_fichier`,
`num_fdr`,
`vdm_localisation`,
`nom_lot`,
`identifiant_unique`,
`empreinte_archivage`,
`archive_num_cfe`,
`archive_num_cfec`,
`archive_chemin_cfec`,
`archive_serial_number`,
`archive_serial_number_2`,
`archive_datetime`,
`chemin_cr_pec`,
`date_depot`,
NOW(),
NOW()
FROM bvrh5_olddb.vdm_trace_cfec;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE tcf_trace_cfec;

-- On ignore 0 colonnes
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('tcf_trace_cfec', 'vdm_trace_cfec', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table nlg_nav_log
Issue de vdm_trace_opn
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_trace_opn' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_trace_opn est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
-- Alimentation
INSERT INTO nlg_nav_log SELECT
`enreg`,
`nav`,
`user`,
`groupe`,
`orsid`,
`pages`,
`octets`,
`id_session`,
`info`,
`adresse_ip`,
`user_agent`,
IF(UCASE(`facturable`)=1,TRUE,FALSE),
TIMESTAMP(`date`,`heure`),
TIMESTAMP(`date`,`heure`)
FROM bvrh5_olddb.vdm_trace_opn;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE nlg_nav_log;

-- On ignore 2 colonnes
SET @ignored_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('nlg_nav_log', 'vdm_trace_opn', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table usr_users
Issue de vdm_users
-------------------------------------*/
-- Test existance
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='vdm_users' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table vdm_users est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
  `usr_langue` VARCHAR(5) NOT NULL DEFAULT 'fr_FR',
  `usr_adp` BOOL NOT NULL DEFAULT 0,
  `usr_beginningdate` DATETIME NOT NULL,
  `usr_endingdate` DATETIME NOT NULL,
  `usr_status` VARCHAR(100) NOT NULL,
  `usr_num_boite_archive` VARCHAR(25) DEFAULT NULL,
  `usr_mail_cycle_de_vie` VARCHAR(250) DEFAULT NULL,
  `usr_right_annotation_doc` SMALLINT NOT NULL DEFAULT 0,
  `usr_right_annotation_dossier` SMALLINT NOT NULL DEFAULT 0,
  `usr_right_classer` SMALLINT NOT NULL DEFAULT 0,
  `usr_right_cycle_de_vie` SMALLINT NOT NULL DEFAULT 0,
  `usr_right_recherche_doc` SMALLINT NOT NULL DEFAULT 0,
  `usr_right_recycler` SMALLINT NOT NULL DEFAULT 0,
  `usr_right_utilisateurs` SMALLINT NOT NULL DEFAULT 0,
  `usr_access_export_cel` BOOL NOT NULL DEFAULT FALSE,
  `usr_access_import_unitaire` BOOL NOT NULL DEFAULT FALSE,
  `usr_created_at` DATETIME,
  `usr_updated_at` DATETIME,
  PRIMARY KEY (`usr_login`),
  UNIQUE KEY `usr_login_pass` (`usr_login`,`usr_pass`),
  KEY `usr_orsid` (`usr_orsid`),
  KEY `usr_groupe` (`usr_groupe`),
  KEY `usr_adp` (`usr_adp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Comptes utilisateurs avec leurs droits';
-- Alimentation
INSERT INTO usr_users SELECT
`login`,
`pass`,
`old_password_list`,
IF(UCASE(`change_pass`)='Y',TRUE,FALSE),
`pass_last_modif`,
IF(UCASE(`pass_never_expires`)='Y',TRUE,FALSE),
IF(UCASE(`must_change_pass`)='Y',TRUE,FALSE),
`session_time`,
IF(UCASE(`actif`)='Y',TRUE,FALSE),
`raison`,
`confidentialite`,
IF(UCASE(`prend_conf_groupe`)='Y',TRUE,FALSE),
`profils`,
`type`,
IF(UCASE(`statistiques`)='Y',TRUE,FALSE),
IF(UCASE(`orsid`)='Y',TRUE,FALSE),
IF(UCASE(`mail`)='Y',TRUE,FALSE),
`adresse_mail`,
`commentaires`,
`nom`,
`prenom`,
`adresse_post`,
`tel`,
`rsoc`,
`groupe`,
IF(UCASE(`export_csv`)='Y',TRUE,FALSE),
IF(UCASE(`export_xls`)='Y',TRUE,FALSE),
IF(UCASE(`export_doc`)='Y',TRUE,FALSE),
IF(UCASE(`recherche_llk`)='Y',TRUE,FALSE),
IF(UCASE(`telechargements`)='Y',TRUE,FALSE),
IF(UCASE(`admin_tele`)='Y',TRUE,FALSE),
IF(UCASE(`tiers_archivage`)='Y',TRUE,FALSE),
`createur`,
`tentatives`,
IF(UCASE(`mail_cci_auto`)='Y',TRUE,FALSE),
`adresse_mail_cci_auto`,
`nb_panier`,
`nb_requete`,
`type_habilitation`,
IF(`langue`>0, 'en_EN', 'fr_FR'),
IF(UCASE(`adp`)='Y',TRUE,FALSE),
`beginningdate`,
`endingdate`,
`status`,
`num_boite_archive`,
`mail_cycle_de_vie`,
CASE UCASE(`annotation`) WHEN 'RW' THEN 7 WHEN 'R' THEN 1 WHEN 'N' THEN 0 END,
CASE UCASE(`annotation_dossier`) WHEN 'RW' THEN 7 WHEN 'R' THEN 1 WHEN 'N' THEN 0 END,
CASE
WHEN UCASE(`classer`)='N' THEN 0
WHEN UCASE(`suppression`)='Y' THEN 7
WHEN UCASE(`modification`)='Y' THEN 3
ELSE 1
END,
CASE
WHEN UCASE(`cycle_de_vie`)='N' THEN 0
WHEN UCASE(`modification`)='Y' THEN 3
ELSE 1
END,
CASE
WHEN (`type_habilitation`!='chef de file expert' AND `type_habilitation`!='expert') THEN 0
WHEN UCASE(`suppression`)='Y' THEN 7
WHEN UCASE(`modification`)='Y' THEN 3
ELSE 1
END,
CASE
WHEN UCASE(`recycler`)='N' THEN 0
WHEN UCASE(`suppression`)='Y' THEN 7
WHEN UCASE(`modification`)='Y' THEN 3
ELSE 1
END,
CASE
WHEN (`type_habilitation`='chef de file' OR `type_habilitation`='chef de file expert') AND (
SELECT `valeur` FROM bvrh5_olddb.bve_config WHERE `variable`='type_abo_visu'
)>1 THEN 3
WHEN (`type_habilitation`='chef de file' OR `type_habilitation`='chef de file expert') THEN 1
ELSE 0
END,
IF(UCASE(`rapport_cel`)='Y',1,0),
IF(UCASE(`envoyer`)='Y',1,0),
`beginningdate`,
`beginningdate`
FROM bvrh5_olddb.vdm_users;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE usr_users;

-- On ignore 1 colonne
SET @ignored_cols=3;
-- On ajoute la colonne usr_right_gerer_utilisateurs et `usr_right_recherche_doc`
SET @added_cols=2;
INSERT INTO tmp_colonnes_tables VALUES
('usr_users', 'vdm_users', @ignored_cols, @added_cols);
SET @added_cols=0;
/*--------------------------------------*/


/*------------------------------------
Création table pro_processus
Issue de bve_processus_perso et bve_processus_perso_user
-------------------------------------*/
-- Test existance bve_processus_perso
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_processus_perso' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_processus_perso est absente\r\n');
LEAVE adp5;
END IF;
-- Test existance bve_processus_perso_user
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_processus_perso_user' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_processus_perso_user est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
DROP TABLE IF EXISTS pro_processus;
CREATE TABLE `pro_processus` (
  `pro_id` INT NOT NULL AUTO_INCREMENT,
  `pro_groupe` SMALLINT NOT NULL,
  `pro_libelle` VARCHAR(100) NULL,
  `pro_id_user` VARCHAR(50) NULL,
  `pro_created_at` DATETIME,
  `pro_updated_at` DATETIME,
  PRIMARY KEY (`pro_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste des processus perso.';
-- Alimentation
INSERT INTO pro_processus SELECT
 pp.id_processus,
 groupe,
 libelle,
 id_user,
 pp.date_creation,
 pp.date_creation
FROM bvrh5_olddb.bve_processus_perso pp LEFT JOIN bvrh5_olddb.bve_processus_perso_user ppu ON pp.id_processus = ppu.id_processus;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE pro_processus;

-- On ignore 1 colonne
SET @ignored_cols=1;
INSERT INTO tmp_colonnes_tables VALUES
('pro_processus', 'bve_processus_perso', @ignored_cols, @added_cols);
/*--------------------------------------*/


/*------------------------------------
Création table pty_processus_type
Issue de bve_processus_perso_type
-------------------------------------*/
-- Test existance bve_processus_perso_type
SET @table_exists=(SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='bve_processus_perso_type' AND TABLE_SCHEMA='bvrh5_olddb');
IF NOT @table_exists THEN
SET err_id=1;
SET err_msg=CONCAT(err_msg,'La table bve_processus_perso_type est absente\r\n');
LEAVE adp5;
END IF;
-- -------------------
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
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Liste des tiroirs pour les processus perso.';
-- Alimentation
INSERT INTO pty_processus_type SELECT
 NULL,
 `id_processus`,
 `type`,
 `date_creation`,
 `date_creation`
FROM bvrh5_olddb.bve_processus_perso_type;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE pty_processus_type;

-- On ignore 0 colonne
SET @ignored_cols=0;
INSERT INTO tmp_colonnes_tables VALUES
('pty_processus_type', 'bve_processus_perso_type', @ignored_cols, @added_cols);
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
-- Alimentation vdm_langue0
INSERT INTO dic_dictionnaire SELECT
NULL,
NULL,
NULL,
NULL,
`variable`,
`valeur`,
'vdm_langue0',
NOW(),
NOW()
FROM bvrh5_olddb.vdm_langue0;
-- Alimentation vdm_langue1
INSERT INTO dic_dictionnaire SELECT
NULL,
NULL,
NULL,
NULL,
`variable`,
`valeur`,
'vdm_langue1',
NOW(),
NOW()
FROM bvrh5_olddb.vdm_langue1;
/*--------------------------------------*/
-- maj des stats
ANALYZE TABLE dic_dictionnaire;

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


/*------------------------------------
Création table ini_interupload_indexation
-------------------------------------*/
DROP TABLE IF EXISTS ini_interupload_indexation;
CREATE TABLE `ini_interupload_indexation` (
  `ini_id` INT NOT NULL AUTO_INCREMENT,
  `ini_ticket` VARCHAR(72) NOT NULL,
  `ini_content` TEXT NOT NULL,
  `ini_created_at` DATETIME,
  `ini_updated_at` DATETIME,
  PRIMARY KEY (`ini_id`),
  UNIQUE KEY `ini_ticket` (`ini_ticket`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/


/*------------------------------------
Création table upr_user_preferences
-------------------------------------*/
DROP TABLE IF EXISTS upr_user_preferences;
CREATE TABLE `upr_user_preferences` (
  `upr_id` INT NOT NULL AUTO_INCREMENT,
  `upr_id_user` VARCHAR(50) NOT NULL,
  `upr_device` VARCHAR(5) NOT NULL,
  `upr_type` VARCHAR(50) NOT NULL,
  `upr_data` TEXT NOT NULL,
  `upr_created_at` DATETIME,
  `upr_updated_at` DATETIME,
  PRIMARY KEY (`upr_id`),
  UNIQUE INDEX `upr_usr_dev_typ` (`upr_id_user`, `upr_device`, `upr_type`),
  CONSTRAINT `fk_upr_usr` FOREIGN KEY (`upr_id_user`) REFERENCES `usr_users`(`usr_login`) ON UPDATE CASCADE
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*--------------------------------------*/

/*--------------------------------------------------------------------*/
/*                                                                    */
/* Vérification des contraintes                                       */
/*                                                                    */
/*--------------------------------------------------------------------*/

-- ------------------------------------------------------------------
-- vérif contrainte com_completude -> usr_users -- Alexandre, le 24 avril 2016
-- ------------------------------------------------------------------
SET @err_fk_com_usr = (SELECT COUNT(*) FROM com_completude com LEFT JOIN usr_users ON com_id_user = usr_login
WHERE usr_login IS NULL);
IF @err_fk_com_usr THEN
SET err_msg=CONCAT(err_msg,'La table com_completude comporte ',@err_fk_com_usr,' lignes non liées à usr_users, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_com_usr\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_com_usr;
CREATE TABLE tmp_lignes_com_usr
SELECT com.* FROM com_completude com LEFT JOIN usr_users ON com_id_user = usr_login
WHERE usr_login IS NULL;
-- suppression des lignes orphelines
DELETE FROM com_completude USING com_completude, tmp_lignes_com_usr WHERE com_completude.com_id_user=tmp_lignes_com_usr.com_id_user;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte cty_completude_type -> com_completude
-- ------------------------------------------------------------------
SET @err_fk_cty_com = (SELECT COUNT(*) FROM cty_completude_type cty LEFT JOIN com_completude com ON cty_id_completude=com_id_completude WHERE com_id_completude IS NULL);
IF @err_fk_cty_com THEN
SET err_msg=CONCAT(err_msg,'La table cty_completude_type comportait ',@err_fk_cty_com,' lignes non liées à com_completude, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_cty_com\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_cty_com;
CREATE TABLE tmp_lignes_cty_com
SELECT cty.* FROM cty_completude_type cty LEFT JOIN com_completude com ON cty_id_completude=com_id_completude WHERE com_id_completude IS NULL;
-- suppression des lignes orphelines
DELETE FROM cty_completude_type USING  cty_completude_type,tmp_lignes_cty_com WHERE cty_completude_type.cty_id=tmp_lignes_cty_com.cty_id;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte cty_completude_type -> typ_type
-- ------------------------------------------------------------------
SET @err_fk_cty_typ = (SELECT COUNT(*) FROM cty_completude_type cty LEFT JOIN typ_type typ ON cty_type=typ_code WHERE typ_type IS NULL);
IF @err_fk_cty_typ THEN
SET err_msg=CONCAT(err_msg,'La table cty_completude_type comportait ',@err_fk_cty_typ,' lignes non liées à typ_type, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_cty_typ\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_cty_typ;
CREATE TABLE tmp_lignes_cty_typ
SELECT cty.* FROM cty_completude_type cty LEFT JOIN typ_type typ ON cty_type=typ_code WHERE typ_type IS NULL;
-- suppression des lignes orphelines
DELETE FROM cty_completude_type USING cty_completude_type, tmp_lignes_cty_typ WHERE cty_completude_type.cty_id=tmp_lignes_cty_typ.cty_id;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte pus_profil_user -> usr_users -- Alexandre, le 22 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_pus_usr = (SELECT COUNT(*) FROM pus_profil_user fus LEFT JOIN usr_users ON pus_id_user = usr_login
WHERE usr_login IS NULL);
IF @err_fk_pus_usr THEN
SET err_msg=CONCAT(err_msg,'La table pus_profil_user comporte ',@err_fk_pus_usr,' lignes non liées à usr_users, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_pus_usr\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_pus_usr;
CREATE TABLE tmp_lignes_pus_usr
SELECT pus.* FROM pus_profil_user pus LEFT JOIN usr_users ON pus_id_user = usr_login
WHERE usr_login IS NULL;
-- suppression des lignes orphelines
DELETE FROM pus_profil_user USING pus_profil_user, tmp_lignes_pus_usr WHERE pus_profil_user.pus_id_user=tmp_lignes_pus_usr.pus_id_user;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte pus_profil_user -> pro_profil -- Alexandre, le 22 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_pus_pro = (SELECT COUNT(*) FROM pus_profil_user fus LEFT JOIN pro_profil ON pus_id_profil = pro_id WHERE pro_id IS NULL);
IF @err_fk_pus_pro THEN
SET err_msg=CONCAT(err_msg,'La table pus_profil_user comporte ',@err_fk_pus_pro,' lignes non liées à pro_profil\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_pus_pro\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_pus_pro;
CREATE TABLE tmp_lignes_pus_pro
SELECT pus.* FROM pus_profil_user pus LEFT JOIN pro_profil ON pus_id_profil = pro_id WHERE pro_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM pus_profil_user USING pus_profil_user, tmp_lignes_pus_pro WHERE pus_profil_user.pus_id_profil=tmp_lignes_pus_pro.pus_id_profil;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte fus_folder_user -> usr_users
-- ------------------------------------------------------------------
SET @err_fk_fus_usr = (SELECT COUNT(*) FROM fus_folder_user fus LEFT JOIN usr_users ON fus_id_user = usr_login
WHERE usr_login IS NULL);
IF @err_fk_fus_usr THEN
SET err_msg=CONCAT(err_msg,'La table fus_folder_user comporte ',@err_fk_fus_usr,' lignes non liées à usr_users, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_fus_usr\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_fus_usr;
CREATE TABLE tmp_lignes_fus_usr
SELECT fus.* FROM fus_folder_user fus LEFT JOIN usr_users ON fus_id_user = usr_login
WHERE usr_login IS NULL;
-- suppression des lignes orphelines
DELETE FROM fus_folder_user USING fus_folder_user, tmp_lignes_fus_usr WHERE fus_folder_user.fus_id_user=tmp_lignes_fus_usr.fus_id_user;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte fus_folder_user -> fol_folder
-- ------------------------------------------------------------------
SET @err_fk_fus_fol = (SELECT COUNT(*) FROM fus_folder_user fus LEFT JOIN fol_folder ON fus_id_folder = fol_id WHERE fol_id IS NULL);
IF @err_fk_fus_fol THEN
SET err_msg=CONCAT(err_msg,'La table fus_folder_user comporte ',@err_fk_fus_fol,' lignes non liées à fol_folder\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_fus_fol\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_fus_fol;
CREATE TABLE tmp_lignes_fus_fol
SELECT fus.* FROM fus_folder_user fus LEFT JOIN fol_folder ON fus_id_folder = fol_id WHERE fol_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM fus_folder_user USING fus_folder_user, tmp_lignes_fus_fol WHERE fus_folder_user.fus_id_folder=tmp_lignes_fus_fol.fus_id_folder;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte fdo_folder_doc -> fol_folder -- Alexandre, le 11 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_fdo_fol = (SELECT COUNT(*) FROM fdo_folder_doc LEFT JOIN fol_folder ON fdo_id_folder = fol_id WHERE fol_id IS NULL);
IF @err_fk_fdo_fol THEN
SET err_msg=CONCAT(err_msg,'La table fdo_folder_doc comporte ',@err_fk_fdo_fol,' lignes non liées à fol_folder\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_fdo_fol\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_fdo_fol;
CREATE TABLE tmp_lignes_fdo_fol
SELECT fdo.* FROM fdo_folder_doc fdo LEFT JOIN fol_folder ON fdo_id_folder = fol_id WHERE fol_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM fdo_folder_doc USING fdo_folder_doc, tmp_lignes_fdo_fol WHERE fdo_folder_doc.fdo_id_folder=tmp_lignes_fdo_fol.fdo_id_folder;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte fdo_folder_doc -> ifp_indexfiche_paperless -- Alexandre, le 14 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_fdo_ifp = (SELECT COUNT(*) FROM fdo_folder_doc LEFT JOIN ifp_indexfiche_paperless ON fdo_id_doc = ifp_id WHERE ifp_id IS NULL);
IF @err_fk_fdo_ifp THEN
SET err_msg=CONCAT(err_msg,'La table fdo_folder_doc comporte ',@err_fk_fdo_ifp,' lignes non liées à ifp_indexfiche_paperless\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_fdo_ifp\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_fdo_ifp;
CREATE TABLE tmp_lignes_fdo_ifp
SELECT fdo.* FROM fdo_folder_doc fdo LEFT JOIN ifp_indexfiche_paperless ON fdo_id_doc = ifp_id WHERE ifp_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM fdo_folder_doc USING fdo_folder_doc, tmp_lignes_fdo_ifp WHERE fdo_folder_doc.fdo_id_doc=tmp_lignes_fdo_ifp.fdo_id_doc;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte ifp_indexfiche_paperless -> typ_type
-- ------------------------------------------------------------------

SET @err_fk_ifp_typ = (SELECT COUNT(*) FROM ifp_indexfiche_paperless ifp LEFT JOIN typ_type ON ifp_id_code_document = typ_code WHERE typ_code IS NULL);
IF @err_fk_ifp_typ THEN
SET err_msg=CONCAT(err_msg,'La table ifp_indexfiche_paperless comporte ',@err_fk_ifp_typ,' lignes non liées à typ_type pour lesquelles ifp_id_code_document a été mis à NULL.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_ifp_typ\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_ifp_typ;
CREATE TABLE tmp_lignes_ifp_typ
SELECT ifp.* FROM ifp_indexfiche_paperless ifp LEFT JOIN typ_type ON ifp_id_code_document = typ_code WHERE typ_code IS NULL;
-- mise à null des ifp_id_code_document orphelins
UPDATE ifp_indexfiche_paperless, tmp_lignes_ifp_typ
SET ifp_indexfiche_paperless.ifp_id_code_document=NULL
WHERE ifp_indexfiche_paperless.ifp_id = tmp_lignes_ifp_typ.ifp_id;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte ifp_indexfiche_paperless -> iin_idx_indiv
-- ------------------------------------------------------------------
SET @err_fk_ifp_iin = (SELECT COUNT(*) FROM ifp_indexfiche_paperless ifp LEFT JOIN iin_idx_indiv ON ifp_id_num_matricule = iin_id_num_matricule AND ifp_id_code_client=iin_id_code_client WHERE iin_id_num_matricule IS NULL);
IF @err_fk_ifp_iin THEN
SET err_msg=CONCAT(err_msg,'La table ifp_indexfiche_paperless comporte ',@err_fk_ifp_iin,' lignes non liées à iin_idx_indiv pour lesquelles ifp_id_num_matricule et ifp_id_code_client ont été mis à NULL.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_ifp_iin\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_ifp_iin;
CREATE TABLE tmp_lignes_ifp_iin
SELECT ifp.* FROM ifp_indexfiche_paperless ifp LEFT JOIN iin_idx_indiv ON ifp_id_num_matricule = iin_id_num_matricule AND ifp_id_code_client=iin_id_code_client WHERE iin_id_num_matricule IS NULL;
UPDATE ifp_indexfiche_paperless, tmp_lignes_ifp_iin
SET ifp_indexfiche_paperless.ifp_id_num_matricule=NULL,
ifp_indexfiche_paperless.ifp_id_code_client=NULL
WHERE ifp_indexfiche_paperless.ifp_id = tmp_lignes_ifp_iin.ifp_id;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte ado_annotations_dossier -> fol_folder -- Alexandre, le 25 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_ado_fol = (SELECT COUNT(*) FROM ado_annotations_dossier LEFT JOIN fol_folder ON ado_folder = fol_id WHERE fol_id IS NULL);
IF @err_fk_ado_fol THEN
SET err_msg=CONCAT(err_msg,'La table ado_annotations_dossier comporte ',@err_fk_ado_fol,' lignes non liées à fol_folder\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_ado_fol\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_ado_fol;
CREATE TABLE tmp_lignes_ado_fol
SELECT ano.* FROM ado_annotations_dossier ano LEFT JOIN fol_folder ON ado_folder = fol_id WHERE fol_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM ado_annotations_dossier USING ado_annotations_dossier, tmp_lignes_ado_fol WHERE ado_annotations_dossier.ado_folder=tmp_lignes_ado_fol.ado_folder;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte ano_annotations_dossier -> usr_users -- Alexandre, le 25 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_ado_usr = (SELECT COUNT(*) FROM ado_annotations_dossier ado LEFT JOIN usr_users ON ado_login = usr_login
WHERE usr_login IS NULL);
IF @err_fk_ado_usr THEN
SET err_msg=CONCAT(err_msg,'La table ado_annotations_dossier comporte ',@err_fk_ado_usr,' lignes non liées à usr_users, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_ado_usr\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_ado_usr;
CREATE TABLE tmp_lignes_ado_usr
SELECT ano.* FROM ado_annotations_dossier ado LEFT JOIN usr_users ON ado_login = usr_login
WHERE usr_login IS NULL;
-- suppression des lignes orphelines
DELETE FROM ado_annotations_dossier USING ado_annotations_dossier, tmp_lignes_ado_usr WHERE ado_annotations_dossier.ado_login=tmp_lignes_ado_usr.ado_login;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte ano_annotations -> ifp_indexfiche_paperless -- Alexandre, le 25 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_ano_ifp = (SELECT COUNT(*) FROM ano_annotations LEFT JOIN ifp_indexfiche_paperless ON ano_fiche = ifp_id WHERE ifp_id IS NULL);
IF @err_fk_ano_ifp THEN
SET err_msg=CONCAT(err_msg,'La table ano_annotations comporte ',@err_fk_ano_ifp,' lignes non liées à ifp_indexfiche_paperless\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_ano_ifp\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_ano_ifp;
CREATE TABLE tmp_lignes_ano_ifp
SELECT ano.* FROM ano_annotations ano LEFT JOIN ifp_indexfiche_paperless ON ano_fiche = ifp_id WHERE ifp_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM ano_annotations USING ano_annotations, tmp_lignes_ano_ifp WHERE ano_annotations.ano_fiche=tmp_lignes_ano_ifp.ano_fiche;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte ano_annotations -> usr_users -- Alexandre, le 25 mars 2016
-- ------------------------------------------------------------------
SET @err_fk_ano_usr = (SELECT COUNT(*) FROM ano_annotations ano LEFT JOIN usr_users ON ano_login = usr_login
WHERE usr_login IS NULL);
IF @err_fk_ano_usr THEN
SET err_msg=CONCAT(err_msg,'La table ano_annotations comporte ',@err_fk_ano_usr,' lignes non liées à usr_users, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_ano_usr\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_ano_usr;
CREATE TABLE tmp_lignes_ano_usr
SELECT ano.* FROM ano_annotations ano LEFT JOIN usr_users ON ano_login = usr_login
WHERE usr_login IS NULL;
-- suppression des lignes orphelines
DELETE FROM ano_annotations USING ano_annotations, tmp_lignes_ano_usr WHERE ano_annotations.ano_login=tmp_lignes_ano_usr.ano_login;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte rce_ref_code_etablissement -> rcs_ref_code_societe -- Alexandre, le 05 avril 2016
-- ------------------------------------------------------------------
SET @err_fk_rce_rcs = (SELECT COUNT(*) FROM rce_ref_code_etablissement rce WHERE rce.rce_id_societe IS NULL);
IF @err_fk_rce_rcs THEN
SET err_msg=CONCAT(err_msg,'La table rce_ref_code_etablissement comporte ',@err_fk_rce_rcs,' lignes non liées à rcs_ref_code_societe, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_rce_rcs\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_rce_rcs;
CREATE TABLE tmp_lignes_rce_rcs
SELECT rce.* FROM rce_ref_code_etablissement rce WHERE rce.rce_id_societe IS NULL;
-- suppression des lignes orphelines
DELETE FROM rce_ref_code_etablissement USING rce_ref_code_etablissement, tmp_lignes_rce_rcs WHERE rce_ref_code_etablissement.rce_id=tmp_lignes_rce_rcs.rce_id;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte pro_processus -> usr_users -- Alexandre, le 24 avril 2016
-- ------------------------------------------------------------------
SET @err_fk_pro_usr = (SELECT COUNT(*) FROM pro_processus pro LEFT JOIN usr_users ON pro_id_user = usr_login
WHERE usr_login IS NULL);
IF @err_fk_pro_usr THEN
SET err_msg=CONCAT(err_msg,'La table pro_processus comporte ',@err_fk_pro_usr,' lignes non liées à usr_users, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_pro_usr\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_pro_usr;
CREATE TABLE tmp_lignes_pro_usr
SELECT pro.* FROM pro_processus pro LEFT JOIN usr_users ON pro_id_user = usr_login
WHERE usr_login IS NULL;
-- suppression des lignes orphelines
DELETE FROM pro_processus USING pro_processus, tmp_lignes_pro_usr WHERE pro_processus.pro_id_user=tmp_lignes_pro_usr.pro_id_user;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte pty_processus_type -> pro_processus
-- ------------------------------------------------------------------
SET @err_fk_pty_pro = (SELECT COUNT(*) FROM pty_processus_type pty LEFT JOIN pro_processus pro ON pty_id_processus=pro_id WHERE pro_id IS NULL);
IF @err_fk_pty_pro THEN
SET err_msg=CONCAT(err_msg,'La table pty_processus_type comportait ',@err_fk_pty_pro,' lignes non liées à pro_processus, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_pty_pro\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_pty_pro;
CREATE TABLE tmp_lignes_pty_pro
SELECT pty.* FROM pty_processus_type pty LEFT JOIN pro_processus pro ON pty_id_processus=pro_id WHERE pro_id IS NULL;
-- suppression des lignes orphelines
DELETE FROM pty_processus_type USING pty_processus_type,tmp_lignes_pty_pro WHERE pty_processus_type.pty_id=tmp_lignes_pty_pro.pty_id;
END IF;
-- ------------------------------------------------------------------
-- vérif contrainte pty_processus_type -> typ_type
-- ------------------------------------------------------------------
SET @err_fk_pty_typ = (SELECT COUNT(*) FROM pty_processus_type pty LEFT JOIN typ_type typ ON pty_type=typ_code WHERE typ_type IS NULL);
IF @err_fk_pty_typ THEN
SET err_msg=CONCAT(err_msg,'La table pty_processus_type comportait ',@err_fk_pty_typ,' lignes non liées à typ_type, elles ont été supprimées.\r\n',
'Pour voir les lignes concernées :\r\n',
'SELECT * FROM tmp_lignes_pty_typ\r\n'
);
DROP TABLE IF EXISTS tmp_lignes_pty_typ;
CREATE TABLE tmp_lignes_pty_typ
SELECT pty.* FROM pty_processus_type pty LEFT JOIN typ_type typ ON pty_type=typ_code WHERE typ_code IS NULL;
-- suppression des lignes orphelines
DELETE FROM pty_processus_type USING pty_processus_type, tmp_lignes_pty_typ WHERE pty_processus_type.pty_id=tmp_lignes_pty_typ.pty_id;
END IF;

/*--------------------------------------------------------------------*/
/*                                                                    */
/* Changement de type afin d'appliquer la clef étrangère              */
/*                                                                    */
/*--------------------------------------------------------------------*/

ALTER TABLE `rce_ref_code_etablissement` CHANGE `rce_id_societe` `rce_id_societe` INT(11) NOT NULL;

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
-- Alexandre, le 22 juin 2016
-- ref_ID_CODE_ETABLISSEMENT -(n,1)-> ref_ID_CODE_SOCIETE
-- les lignes orphelines ont été supprimées
ALTER TABLE rce_ref_code_etablissement ADD CONSTRAINT fk_rce_rcs FOREIGN KEY (rce_id_societe) REFERENCES rcs_ref_code_societe(rcs_id)
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

/* code pour suppression des contraintes
ALTER TABLE cty_completude_type DROP FOREIGN KEY fk_com_cty;
ALTER TABLE cat_completude_auto_type DROP FOREIGN KEY fk_cal_cat;
ALTER TABLE cty_completude_type DROP FOREIGN KEY fk_typ_cty;
ALTER TABLE fus_folder_user DROP FOREIGN KEY fk_fus_usr;
ALTER TABLE fus_folder_user DROP FOREIGN KEY fk_fus_fol;
ALTER TABLE ifp_indexfiche_paperless DROP FOREIGN KEY fk_ifp_typ;
Autres infos
SELECT * FROM information_schema.KEY_COLUMN_USAGE; -- contient toutes les infos sur les contraintes
*/

-- Alexandre, le 02 février 2016
-- C'est ici qu'on fait une comparaison entre le nombre de champs de la nouvelle table et de l'ancienne
SELECT DATABASE() INTO @dbname;
BEGIN
  DECLARE fin BOOLEAN DEFAULT FALSE;
  DECLARE nbre_cols_table_newdb INTEGER DEFAULT 0;
  DECLARE nbre_cols_table_bvrh5_olddb INTEGER DEFAULT 0;
  DECLARE nbre_cols_diff INTEGER DEFAULT 0;

  DECLARE nom_table_newdb VARCHAR(50);
  DECLARE nom_table_bvrh5_olddb VARCHAR(50);

  DECLARE ignored_cols TINYINT;
  DECLARE added_cols TINYINT;

  DECLARE cur1 CURSOR FOR SELECT * FROM tmp_colonnes_tables;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin=TRUE;
  OPEN cur1;
  REPEAT
    FETCH cur1 INTO nom_table_newdb, nom_table_bvrh5_olddb, ignored_cols, added_cols;
    SET nbre_cols_table_newdb=(SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_NAME=nom_table_newdb AND TABLE_SCHEMA=@dbname);
    SET nbre_cols_table_bvrh5_olddb=(SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_NAME=nom_table_bvrh5_olddb AND TABLE_SCHEMA='bvrh5_olddb');

    -- On retire les colonnes ajoutées _created_at et _updated_at dans les tables de la nouvelle BDD
    SET nbre_cols_table_newdb=nbre_cols_table_newdb-2;
	  -- On retire les colonnes créées dans la table de la nouvelle BDD qui n'existent pas dans la table de l'ancienne BDD
	  SET nbre_cols_table_newdb=nbre_cols_table_newdb-added_cols;

    SET nbre_cols_diff=nbre_cols_table_bvrh5_olddb-nbre_cols_table_newdb-ignored_cols;
    IF nbre_cols_diff>0 THEN
      SET err_msg=CONCAT(err_msg, 'Une ou plusieurs colonnes de la table ', nom_table_bvrh5_olddb, ' n''ont pas été créées dans la table ', nom_table_newdb, '.\r\nAvez-vous oublier d''ignorer des colonnes ?\r\n');
    ELSE
      IF nbre_cols_diff<0 THEN
        SET err_msg=CONCAT(err_msg, 'Une ou plusieurs colonnes ont été créées dans la table ', nom_table_newdb, ' et n''existent pas dans la table ', nom_table_bvrh5_olddb, '.\r\n');
      END IF;
    END IF;
    IF nbre_cols_diff!=0 THEN
      SET err_msg=CONCAT(err_msg, 'Veuillez contrôler: ', nom_table_newdb, ': ', nbre_cols_table_newdb, ' - ', nom_table_bvrh5_olddb, ': ', nbre_cols_table_bvrh5_olddb, ' - colonnes ignorées: ', ignored_cols, ' - colonnes ajoutées: ', added_cols, '.\r\n');
    END IF;
  UNTIL fin END REPEAT;
  CLOSE cur1;
END;

DROP TEMPORARY TABLE tmp_colonnes_tables;

/*-------------------------*/

SET err_id=IF(@err_fk_com_usr+@err_fk_cty_com+@err_fk_cty_typ+@err_fk_pus_usr+@err_fk_pus_pro+@err_fk_fus_usr+@err_fk_fus_fol+@err_fk_fdo_fol+@err_fk_fdo_ifp+@err_fk_ifp_typ+@err_fk_ifp_iin+@err_fk_ado_fol+@err_fk_ado_usr+@err_fk_ano_ifp+@err_fk_ano_usr+@err_fk_rce_rcs+@err_fk_pty_pro+@err_fk_pty_typ>0,1,0);
END$$
DELIMITER ;

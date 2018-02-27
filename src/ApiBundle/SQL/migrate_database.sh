#!/bin/bash

# Cette fonction quitte si le code erreur est > 0 ou = 9
# Un message associé au code erreur est également retourné
quitte() {
	if [ $1 -gt 0 ]
	then
		if [ $1 -eq 9 ]
		then
			echo '-----> ... Abandon !'
		else
			echo '-----> ... Erreur !!'
		fi
		exit
	fi
}

# Cette fonction supprime un fichier passé en paramètre
supprime() {
	if [ -e $1 ] && [ -f $1 ]
	then
		rm $1
	fi
}

echo -e "+====================+\nBienvenue"

#########
# DEBUT #
#########

if [ $# -eq 0 ]
# Si pas de paramètres saisis, on passe en mode intéractif
then
	interactif=1
else
	# S'il manque un ou plusieurs paramètres, on demande soit la saisie de tous les paramètres, soit on passe en mode intéractif
	if [ $# -ne 8 ]
	then
		echo -e "Les paramètres sont incomplets ou non renseignés.\nLa liste des paramètres est\n- L'IP du serveur où récupérer la base de données\n- Le port d'écoute (Par défaut: 3306)\n- Un compte utilisateur\n- Un mot de passe\n- Le n° d'instance à récupérer\n- L'IP du serveur cible\n- Le compte utilisateur associé\n- Le mot de passe associé"
		read -p 'Souhaitez-vous passer en mode intéractif ? (O/n) ' choix
		while [ $choix != 'O' ] && [ $choix != 'n' ]
		do
			read -p 'Erreur de saisie. Veuillez recommencer ! (O/n) ' choix
		done
		if [ $choix = 'O' ]
		then
			interactif=1
		else
			quitte 9
		fi
	else
		interactif=0
	fi
fi

if [ $interactif -eq 0 ]
then
	# IP ou host du serveur source
	hoteSource=$1
	# Port
	port=$2
	# Compte utilisateur
	userSource=$3
	# MDP
	passwdSource=$4
	# Instance
	instance=$5
	# IP ou host du serveur de destination
	hoteDestination=$6
	# Compte utilisateur associé
	userDestination=$7
	# MPD associé
	passwdDestination=$8
else
	# Demande de saisie des paramètres
	echo -e "Saisie des paramètres\nSource"
	read -p "L'IP du serveur où récupérer la base de données : " hoteSource
	read -p "Le port d'écoute (3306 si vide) : " port
	read -p 'Le compte utilisateur : ' userSource
	read -s -p 'Le mot de passe : ' passwdSource
	echo ''
	read -p "Le n° d'instance : " instance

	echo 'Destination'
	read -p "L'IP du serveur de destination : " hoteDestination
	read -p 'Le compte utilisateur associé au serveur : ' userDestination
	read -s -p 'Le mot de passe associé au serveur : ' passwdDestination
	echo ''
fi

# Port d'écoute = 3306 si non saisi
if [ -z $port ]
then
	port=3306
fi

###################################
# MIGRATION DE LA BASE DE DONNEES #
###################################

ancienneInstance=bvrh_$instance
nouvelleInstance=bvrh5_$instance
# Suppression de la sauvegarde
supprime dump.sql

#####
# 1. Récupération de la sauvegarde
#####
echo -e "+--------------------+\n-----> 1. Sauvegarde de la database $ancienneInstance\nVeuillez patienter..."
mysqldump -h $hoteSource --port=$port -u $userSource --password=$passwdSource $ancienneInstance --compress --quick > dump.sql
quitte $?
echo '-----> ... Fin de la sauvegarde'

#####
# 2. Import
#####
echo -e "+--------------------+\n-----> 2. Import de la sauvegarde sur $hoteDestination\nVeuillez patienter..."
# Suppression de l'éventuelle base bvrh5_olddb
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DROP DATABASE IF EXISTS `bvrh5_olddb`;'
quitte $?
# Création
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'CREATE DATABASE `bvrh5_olddb`;'
quitte $?
# Intégration de la sauvegarde
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination --max_allowed_packet=100M bvrh5_olddb < dump.sql
quitte $?
echo '-----> ... Import terminé'

#####
# 3. Création de la database
#####
echo -e "+--------------------+\n-----> 3. Initialisation de la database $nouvelleInstance\nVeuillez patienter..."

# Test d'existance: on informe l'utilisateur si elle existe déjà
existe=$(mysql -h $hoteDestination -u $userDestination --password=$passwdDestination --skip-column-names -e "SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name='$nouvelleInstance';")

if [ $existe -eq 1 ]
then
	read -p "La base $nouvelleInstance existe déjà ! Si vous continuez, la base actuelle sera supprimée. Continuer ? (O/n) " reponse
	while [ $reponse != 'O' ] && [ $reponse != 'n' ]
	do
		read -p 'Erreur de saisie. Continuer ? (O/n) ' reponse
	done
	if [ $reponse = 'O' ]
	then
		# Suppression de la base existante
		mysql -h $hoteDestination -u $userDestination --password=$passwdDestination -e "DROP DATABASE $nouvelleInstance;"
		quitte $?
	else
		# Abandon
		quitte 9
	fi
fi

# Création de la database
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination -e "CREATE DATABASE $nouvelleInstance;"
quitte $?
# Intégration de la procédure
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination $nouvelleInstance < /data/sites/api/src/ApiBundle/SQL/migrate_database.sql
quitte $?
echo '-----> ... Initialisation terminée'

#####
# 4. Migration
#####
echo -e "+--------------------+\n-----> 4. Migration en cours\nVeuillez patienter..."
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'CALL to_adp5(@err_id, @err_msg);SELECT @err_msg;'
quitte $?
echo '-----> ... Fin de la migration'

#####
# 5. Nettoyage
#####
echo -e "+--------------------+\n-----> 5. Nettoyage en cours\nVeuillez patienter..."
# Suppression de la database bvrh5_olddb
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DROP DATABASE IF EXISTS `bvrh5_olddb`;'
quitte $?
# Suppression de la procédure
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DROP PROCEDURE IF EXISTS `to_adp5`;'
quitte $?
# Suppression de la sauvegarde
supprime dump.sql
echo '-----> ... Fin du nettoyage'

#################################
# MIGRATION TRADUCTIONS METIERS #
#################################

#####
# 6. Migration des traductions métiers
#####
echo -e "+--------------------+\n-----> 6. Migration des traductions en cours\nVeuillez patientier..."
php /data/sites/api/app/console api:migrate_translations_database_command --instance=$instance

if [ $? -gt 0 ]
then
	echo -e "-----> ... Lancement impossible.\nVeuillez exécuter manuellement la commande '/data/sites/api/app/console api:migrate_translations_database_command --instance=$instance'"
	quitte $?
fi

echo '-----> ... Fin de la migration'

###############################
# MIGRATION TRADUCTIONS FRONT #
###############################

#####
# 7. Intégration
#####
echo -e "+--------------------+\n-----> 7. Intégration du dictionnaire en cours\nVeuillez patienter..."
php /data/sites/api/app/console api:load_translations_command --instance=$instance

if [ $? -gt 0 ]
then
    echo -e "-----> ... Lancement impossible.\nVeuillez exécuter manuellement la commande '/data/sites/api/app/console api:load_translations_command --instance=$instance'"
    quitte $?
fi

echo "-----> ... Fin de l'intégration"

##################################
# FIN DES MIGRATIONS TRADUCTIONS #
##################################

#####
# 8. Nettoyage
#####
echo -e "+--------------------+\n-----> 8. Nettoyage en cours des traductions\nVeuillez patientier..."
# Suppression des lignes inutiles
echo '------ Suppression des lignes inutiles du dictionnaire'
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DELETE FROM `dic_dictionnaire` WHERE dic_code IS NULL;'
quitte $?
# Suppression des colonnes variable - valeur - provenance
#echo '------ Suppression des colonnes `variable` - `valeur` - `provenance`'
#mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'ALTER TABLE `dic_dictionnaire` DROP COLUMN `dic_old_variable`, DROP COLUMN `dic_old_valeur`, DROP COLUMN `dic_old_provenance`;'
#quitte $?
# Purge la0_langue0
echo '------ Purge de la0_langue0'
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DROP TABLE `la0_langue0`;'
quitte $?
# Purge la1_langue1
echo '------ Purge de la1_langue1'
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DROP TABLE `la1_langue1`;'
quitte $?
echo '-----> ... Fin du nettoyage'

#######
# FIN #
#######
echo -e "+====================+\nFin de la migration !!!"

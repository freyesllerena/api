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
	if [ $# -ne 5 ]
	then
		echo -e "Les paramètres sont incomplets ou non renseignés.\nLa liste des paramètres est\n- L'IP du serveur\n- Le port d'écoute (Par défaut: 3306)\n- Un compte utilisateur\n- Un mot de passe\n- Le n° d'instance à créer"
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
	# IP ou host du serveur de destination
	hoteDestination=$1
	# Port
	port=$2
	# Compte utilisateur
	userDestination=$3
	# MDP
	passwdDestination=$4
	# Instance
	instance=$5
else
	# Demande de saisie des paramètres
	echo -e "Saisie des paramètres\nDestination"
	read -p "L'IP du serveur : " hoteDestination
	read -p "Le port d'écoute (3306 si vide) : " port
	read -p 'Le compte utilisateur associé au serveur : ' userDestination
	read -p 'Le mot de passe associé au serveur : ' passwdDestination
	read -p "Le n° d'instance : " instance
fi

# Port d'écoute = 3306 si non saisi
if [ -z $port ]
then
	port=3306
fi

###################################
# CREATION DE LA BASE DE DONNEES #
###################################

nouvelleInstance=bvrh5_$instance

#####
# 1. Création de la database
#####
echo -e "+--------------------+\n-----> 1. Initialisation de la database $nouvelleInstance\nVeuillez patienter..."

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
mysql -h $hoteDestination -u $userDestination --password=$passwdDestination $nouvelleInstance < /data/sites/api/src/ApiBundle/SQL/create_database.sql
quitte $?
echo '-----> ... Initialisation terminée'

#####
# 2. Création des tables
#####
echo -e "+--------------------+\n-----> 2. Création des tables en cours\nVeuillez patienter..."
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'CALL create_adp5();'
quitte $?
echo '-----> ... Fin de la création des tables'

#####
# 3. Nettoyage
#####
echo -e "+--------------------+\n-----> 3. Nettoyage en cours\nVeuillez patienter..."
# Suppression de la procédure
mysql $nouvelleInstance -h $hoteDestination -u $userDestination --password=$passwdDestination -e 'DROP PROCEDURE IF EXISTS `create_adp5`;'
quitte $?
echo '-----> ... Fin du nettoyage'

###############################
# MIGRATION TRADUCTIONS FRONT #
###############################

#####
# 4. Intégration
#####
echo -e "+--------------------+\n-----> 4. Intégration du dictionnaire en cours\nVeuillez patienter..."
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

#######
# FIN #
#######
echo -e "+====================+\nFin de la création !!!"

#!/bin/bash
dir=$1
# -*- ENCODING: UTF-8 -*-
# crontab program : 0  1  *  *  *    /data/sites/api/php app/console api:purge_upload_temp_command
# Ce script a besoin de l'emplacement du dossier des fichiers à effacer.
# Il efface s'il trouve le nom du dossier "upload_temp"
# Effacement de fichiers de plus de 1440 minutes (24h)
if [[ $1 == *"upload_temp"* ]]
then
    find $dir* -type f -mmin +1440 -delete
fi
exit

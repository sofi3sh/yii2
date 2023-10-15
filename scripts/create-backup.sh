#!/bin/bash

NOW=$(date +%m-%d-%y_%X)
FILENAME="Backup_$NOW"
 
#create dump and saving it in the current directory 
docker exec metalpark_mysql_1 mysqldump -u metalpark --password=metalpark metalpark > backup.sql

#creating a new archive and take a dump and the data there
tar czf "$FILENAME.tar.gz" web/uploads backup.sql
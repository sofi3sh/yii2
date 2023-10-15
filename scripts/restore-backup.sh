#!/bin/bash

#check whether the given file siutable for performing restoring from it
#required following conditions
# - file extension is .tar.gz
# - file has dump.sql and uploads in it
validateFile() {
    if test -f "$1"; then
        if [[ $1 == *tar.gz ]]; then
            FILES_LISTING=$(tar -tvf $1)
            if [[ $FILES_LISTING == *"dump.sql"* && $FILES_LISTING == *"uploads"* ]]; then
                echo 1
            else
                echo 0
            fi
        else
            echo 0
        fi
    else
        echo 0
    fi
}

askConfirmation() {
    read -r -p "Are you sure that you wanna restore DB and data from this file? [y/N] " response
    if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
        echo 1
    else
        echo 0
    fi
}

#check whether command line argument was passed
if [ -n "$1" ]; then
    if [ "$1" == -help ]; then
        echo "Usage ./run-restore [path to backup file]"
        exit 1
    fi

    IS_VALID=$(validateFile "$1")
    if [[ $IS_VALID == '0' ]]; then
        echo 'Sorry, given file does not exist or not suitable for backup, make sure you spelled its name correctly or pick another one'
        exit 1
    fi

    echo -e "\033[0;31m[WARNING!] All the current data will be replaced with data from backup file. Do it if you really understand what you doing.\033[0m"
    CONFIRMED=$(askConfirmation)
    if [[ $CONFIRMED == '0' ]]; then
        echo 'Cancelled'
        exit 1
    fi

    #here goes process of extraction
    mkdir temp
    tar -xf $1 -C temp/

    #then we delete everything from /uploads folder
    rm -rf uploads/*

    #and copy files from folder with extracted stuff to there
    mv temp/uploads/* uploads

    #then we restore DB
    docker exec metalpark_mysql_1 mysql --user=metalpark --password=metalpark --database=metalpark < temp/dump.sql

    #and clean after ourselves
    rm -rf temp

    echo "Backup restore was successfull"
else
    echo "Please, specify target file for restoring"
fi

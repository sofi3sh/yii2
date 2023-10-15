#!/bin/bash

cd /var/www/app
echo "=> working directory: $(pwd)"

echo "=> stash local changes if exist"
git add .
git status -s
git stash

echo "=> pull changes from repo"
git checkout master
git fetch origin master
git status
git pull

echo "=> build info"
git log -1

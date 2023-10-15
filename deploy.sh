#!/bin/bash

echo "=> run app's migrations"
yes | php yii.php migrate

echo "=> run backfill script"
php yii.php backfill/database

echo "=> install frontend dependencies"
cd frontend && npm install

echo "=> build frontend"
npm run build-production

echo "=> install limitless dependencies"
cd ../web/themes/limitless && npm install

echo "=> build limitless dependencies"
npm run gulp -- build

echo "=> deployment finished"

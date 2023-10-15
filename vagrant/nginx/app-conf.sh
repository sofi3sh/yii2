#!/usr/bin/env bash
set -x
export DEBIAN_FRONTEND=noninteractive

PROJECT=$1
if [ ! -n "$PROJECT" ]; then
    echo "Project name argument is missing!"
    exit 1
fi

echo "Setup Nginx ..."
echo "server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    charset utf-8;
    client_max_body_size 128M;
    sendfile off;

    server_name $PROJECT.loc;
    root /app/web/;

    index index.php index.html index.htm;

    access_log off;
    error_log   /app/vagrant/nginx/log/$PROJECT.error.log;

    location ~ \/themes {
        root /app;
    }

    location ~ \/assets {
        root /app/web;
    }

    location = /favicon.ico {
        return 204;
        access_log off;
        log_not_found off;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param APP_ENV 'dev';
        include fastcgi_params;

        # Increase timeout for xdebugging
        fastcgi_read_timeout 15m;
    }
}
" > /etc/nginx/sites-available/default
ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled

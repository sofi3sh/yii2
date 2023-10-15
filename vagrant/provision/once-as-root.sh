#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")
project=$(echo "$2")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Adding repos"
add-apt-repository ppa:ondrej/php -y
add-apt-repository ppa:nginx/stable -y
apt-key adv --keyserver ha.pool.sks-keyservers.net --recv-keys 5072E1F5
echo "deb http://repo.mysql.com/apt/ubuntu/ xenial mysql-8.0" | tee -a /etc/apt/sources.list.d/mysql.list

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.3-fpm php7.3-cli php7.3-curl php7.3-intl php7.3-mysql php7.3-gd php7.3-mbstring php7.3-xml unzip nginx mysql-server php.xdebug

info "Configuring MySQL ..."
chmod 777 /var/run/mysqld/mysqld.sock
service mysql start
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';"
mysql -e "CREATE USER 'root'@'%'; GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;"
mysql -e "CREATE USER 'vagrant'@'localhost'; GRANT ALL PRIVILEGES ON *.* TO 'vagrant'@'localhost' WITH GRANT OPTION;"
sed -i 's/bind-address/# bind-address/' /etc/mysql/mysql.conf.d/mysqld.cnf
service mysql restart
echo "Done!"


info "Configuring PHP ..."
sed -i "s/user = www-data/user = vagrant/" /etc/php/7.3/fpm/pool.d/www.conf
sed -i "s/group = www-data/group = vagrant/" /etc/php/7.3/fpm/pool.d/www.conf
sed -i "s/listen\.owner.*/listen.owner = vagrant/" /etc/php/7.3/fpm/pool.d/www.conf
sed -i "s/listen\.group.*/listen.group = vagrant/" /etc/php/7.3/fpm/pool.d/www.conf
sed -i "s/;listen\.mode.*/listen.mode = 0666/" /etc/php/7.3/fpm/pool.d/www.conf

sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.3/fpm/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.3/fpm/php.ini
sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/7.3/fpm/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/7.3/fpm/php.ini
sed -i "s/upload_max_filesize = .*/upload_max_filesize = 100M/" /etc/php/7.3/fpm/php.ini
sed -i "s/post_max_size = .*/post_max_size = 100M/" /etc/php/7.3/fpm/php.ini
sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/7.3/fpm/php.ini

sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.3/cli/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.3/cli/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/7.3/cli/php.ini
sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/7.3/cli/php.ini

echo "
xdebug.remote_enable = 1
xdebug.remote_autostart = 1
xdebug.remote_connect_back = 1
xdebug.remote_port = 9000
" >> /etc/php/7.3/fpm/conf.d/20-xdebug.ini
service php7.3-fpm restart
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Removing default site configuration"
rm /etc/nginx/sites-enabled/default
echo "Done!"

info "Initailize databases for MySQL"
mysql -e "CREATE DATABASE \`$project\`;"
mysql -e "CREATE DATABASE \`${project}_test\`;"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

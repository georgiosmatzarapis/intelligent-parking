#!/bin/bash

# Updating repository
sudo apt-get -y update;
sudo apt-get -y upgrade;

# Installing Apache
sudo apt-get -y install apache2;

# Copy config
configFile='001-project-web.conf';
cd /etc/apache2/sites-enabled;
sudo cp /vagrant/$configFile .;
if [ -f 000-default.conf ]; then
    sudo rm -f 000-default.conf;
fi
cd -;

# Installing MySQL and it's dependencies, Also, setting up root password for MySQL as it will prompt to enter the password during installation
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password rootpass';
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password rootpass';
sudo apt-get -y install mysql-server;
sudo apt-get -y install mysql-client;

# Secure MySQL
# mysql_secure_installation;

# Installing PHP and it's dependencies
sudo apt-get -y install php7.2 libapache2-mod-php7.2  php7.2-mysql php7.2-xml;

# Make PHP modifications
phpModFile='increasePhpUpload';
cd /etc/php/7.2/mods-available;
sudo cp /vagrant/$phpModFile".ini" .;
sudo phpenmod $phpModFile;
cd -;

# Restart apache2
sudo systemctl restart apache2;

# Set correct time
sudo rm /etc/localtime;
sudo ln -s /usr/share/zoneinfo/Europe/Athens /etc/localtime;

echo "VM IPs:";
hostname -I;
echo "IF THIS THE FIRST TIME YOU RUN THIS YOU SHOULD UPLOAD THE DATABASE!";
#!/bin/bash

# Copy config
configFile='001-project-web.conf';
sudo cp /vagrant/$configFile /etc/apache2/sites-enabled;

# Restart apache2
sudo systemctl restart apache2;
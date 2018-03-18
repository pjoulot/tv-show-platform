#!/bin/sh

########## Some default values ##########
FEATURES=0
ALLOW_SSH=1
UPDATE_CONF=0



########## General variables ##########
SYSTEM="Debian"
USER="drupal"
GROUP=10000



########## Drupal variables ##########
DRUPAL_PROFIL="standard"
DRUPAL_LOCALE="en"
DRUPAL_ADMIN_USER=admin
DRUPAL_ADMIN_PWD=drupal
if [ -z "$DRUPAL_DB_URL" ]
then
    DRUPAL_DB_URL=mysql://sgo:sgo@localhost/sgo
fi
if [ -z "$DRUPAL_ADMIN_MAIL" ]
then
    DRUPAL_ADMIN_MAIL="admin@example.com"
fi



########## Project variables ##########
PROJECT_PATH="/home/drupal/sgo"



########## Environment variables ##########
case "$ENV" in
    dev)
        WWW_PATH="/var/www/sgo/web"
        FEATURES=0
        ALLOW_SSH=0
        UPDATE_CONF=0
        ;;
esac



########## Calculated variables ##########
if [ $SYSTEM = "redhat" ]
then
    APACHE_USER="apache"
    APACHE_SERVICE="httpd"
else
    APACHE_USER="www-data"
    APACHE_SERVICE="apache2"
fi


#!/bin/sh

cd /home/philippe/tvshow/
composer update
cd web
drush cc drush
drush updatedb -y
featuresEnabled=`drush pml | grep features`
if [[ $featuresEnabled = *"Enabled"* ]]; then
  drush fra -y
fi
drush entity-updates -y
localeEnabled=`drush pml | grep locale`
if [[ $localeEnabled = *"Enabled"* ]]; then
  drush locale-check
  drush locale-update
fi
drush cr
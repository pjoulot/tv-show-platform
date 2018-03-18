#!/bin/sh

########## Init variables ##########

# Default variables initialization.
ENV=;
TAG=;

# CHECK OPTIONS
for VAR in "$@"
do
    case $VAR in
        info|help )
            echo "Usage: ./`basename $0` [options]";
            echo "";
            echo "  * option env :"
            echo "    env=[ENV]               Target environment"
            echo "";
            echo "  * option tag :"
            echo "    tag=[YYYYMMDD_HHMM]     Git tag"
            echo "";
            exit 0;;
        --env* )
            ENV=$(echo $VAR | cut -d "=" -f 2);;
        --tag* )
            TAG=$(echo $VAR | cut -d "=" -f 2);;
        * )
            echo "Unknown argument '$VAR'.";;
    esac
done

# Check variables.
if [ -z "$TAG" ]
then
    echo "missing argument 'tag'."
    exit 0
fi
if [ -z "$ENV" ]
then
    echo "missing argument 'env'."
    exit 0
fi

# Stop executing the script if any command fails.
# See http://stackoverflow.com/a/4346420/442022 for details.
set -e
set -o pipefail

echo "Changing to the environment branch"
git checkout $ENV

echo "Purging local modifications..."
git reset --hard HEAD

echo "Grabbing the latest code from the repository..."
git fetch --tags

echo "Switch to appropriate tag (${TAG})..."
git checkout tags/$TAG

# Create the folder with the files that need to be updated.
mkdir /home/drupal/releases/$TAG
mkdir /home/drupal/releases/$TAG/web
mkdir /home/drupal/releases/$TAG/web/themes
mkdir /home/drupal/releases/$TAG/web/modules
mkdir /home/drupal/releases/$TAG/web/sites
mkdir /home/drupal/releases/$TAG/web/sites/default
# Copy the custom modules
cp -R /home/drupal/sgo/src/modules/custom /home/drupal/releases/$TAG/web/modules/
# Copy the custom themes
cp -R /home/drupal/sgo/src/themes/custom /home/drupal/releases/$TAG/web/themes/
# Copy the composer.json
cp -f /home/drupal/sgo/conf/drupal/composer.json /home/drupal/releases/$TAG/
# Copy the local settings and services files
cp -f /home/drupal/sgo/conf/drupal/default/settings.local.php /home/drupal/releases/$TAG/web/sites/default/
cp -f /home/drupal/sgo/conf/drupal/default/local.services.yml /home/drupal/releases/$TAG/web/sites/default/

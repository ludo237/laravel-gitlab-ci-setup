#!/usr/bin/env bash
export SSHPASS=$GITLAB_SSH_PASS

# Install dependencies only for Docker.
[[ ! -e /.dockerenv ]] && exit 0
set -xe

# Add repository to /etc/apt/source.list
echo "deb http://ftp.uk.debian.org/debian jessie-backports main" > /etc/apt/sources.list.d/backports.list
# Sanity test
cat /etc/apt/sources.list.d/backports.list

# Update packages and install composer and PHP dependencies.
apt-get update -y -qq
apt-get install -y -qq apt-utils apt-transport-https build-essential git sshpass iputils-ping gnupg libcurl4-gnutls-dev

# Connect via SSHPASS to the production directory
# Call Envoy.blade.php which will do the rest
sshpass -V
sshpass -e ssh -p $PRODUCTION_PORT -o stricthostkeychecking=no -t $PRODUCTION_USERNAME@$PRODUCTION_HOST "cd $PRODUCTION_DIR_PATH; $PRODUCTION_COMMAND" 

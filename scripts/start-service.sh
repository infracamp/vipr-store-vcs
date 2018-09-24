#!/bin/bash

set -e

echo "Setting user rights on /srv"

sudo mkdir -p /srv/ssh
sudo chown -R www-data /srv


if [ ! -f /srv/ssh/ssh_key ]
then
    echo "SSH Key /srv/ssh_key not existing - generating new one"
    sudo ssh-keygen -t ed25519 -N "" -f /srv/ssh/ssh_key
    sudo chown -R www-data /srv/ssh
fi;


echo "SSH Public Key is:"
echo "";
cat /srv/ssh/ssh_key.pub

echo "";
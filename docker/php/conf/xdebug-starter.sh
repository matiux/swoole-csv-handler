#!/usr/bin/env bash

set -e

# if `HOST_IP` is manually configured as env
XDEBUG_HOST="$HOST_IP"

if [[ "$OSTYPE" == "linux-gnu"* ]]; then
  # try to get the linux host ip
  #if [[ -z "$HOST" ]]; then
  #  HOST=$(ip route | awk 'NR==1 {print $3}')
  #fi
  XDEBUG_HOST=$(ip route | awk 'NR==1 {print $3}')
elif [[ "$OSTYPE" == "darwin"* ]]; then
  #echo 'Mac'
  # try to get the ip of the host from ns host.docker.internal
  #if [[ -z "$HOST" ]]; then
  #  HOST=$(getent hosts host.docker.internal | awk '{ print $1 }')
  #fi
  XDEBUG_HOST=$(getent hosts host.docker.internal | awk '{ print $1 }')
elif [[ "$OSTYPE" == "cygwin" ]]; then
  #echo 'Win1'
  XDEBUG_HOST=$(ip route | awk 'NR==1 {print $3}')
elif [[ "$OSTYPE" == "msys" ]]; then
  #echo 'Win2'
  XDEBUG_HOST=$(ip route | awk 'NR==1 {print $3}')
elif [[ "$OSTYPE" == "win32" ]]; then
  #echo 'O.o'
  XDEBUG_HOST=$(ip route | awk 'NR==1 {print $3}')
elif [[ "$OSTYPE" == "freebsd"* ]]; then
  #echo 'Freebsd'
  XDEBUG_HOST=$(ip route | awk 'NR==1 {print $3}')
else
  # use the ip alias loopback
  if [ -z "$HOST" ]; then
    XDEBUG_HOST=$(10.254.254.254)
  fi
fi

#Alpine - Debian
if [[ -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ]]; then
  sed -i "s/xdebug\.remote_host=.*/xdebug\.remote_host=${XDEBUG_HOST}/" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
fi
#Ubuntu
#if [[ -f /etc/php/7.4/mods-available/xdebug.ini ]]; then
#  sed -i "s/xdebug\.remote_host=.*/xdebug\.remote_host=${HOST}/" /etc/php/7.4/mods-available/xdebug.ini
#fi

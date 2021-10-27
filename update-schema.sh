#!/bin/bash

# check that docker command is runnable
if ! command -v docker &> /dev/null
then
  echo 'Unable to find "docker" command, make sure it is in your $PATH'
  exit 1
fi

# get ID of container for fituska_php73 where we'll run doctrine command for updating database scheme
CONTAINER_ID=$(docker ps --filter name=fituska_php73 -q)
if [[ -z $CONTAINER_ID ]]
then
  echo "No container named shieldedinvoices_php73 is running, try running 'docker-composer up'"
  exit 1
fi

docker exec -it $CONTAINER_ID php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql
#!/bin/bash

default_env=staging
env=${1:-$default_env}
batch_size=1000

./app/console cache:clear --no-warmup -e=$env
./app/console cache:warmup -e=$env
./app/console doctrine:mongodb:fixtures:load --fixtures=src/Lighthouse/CoreBundle/DataFixtures/Kesko/ -e=$env
./app/console doctrine:mongodb:schema:update -e=$env
./app/console lighthouse:import:products fixtures/kesko-goods.xml --batch-size=$batch_size -e=$env

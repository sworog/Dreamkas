#!/bin/bash

default_env=staging
default_receipts=~/amn/

env=${1:-$default_env}
batch_size=1000
receipts=${2:-$default_receipts}
project=amn

./app/console cache:clear --no-warmup -e=$env
./app/console cache:warmup -e=$env

./app/console doctrine:mongodb:schema:drop -e=$env
./app/console doctrine:mongodb:schema:create -e=$env

./app/console lighthouse:user:create -e=$env --customProjectName=$project owner@lighthouse.pro lighthouse

./app/console doctrine:mongodb:fixtures:load -e=$env --append --fixtures=src/Lighthouse/CoreBundle/DataFixtures/AMN/

./app/console lighthouse:import:products -e=$env fixtures/amn-goods.xml --batch-size=$batch_size --project=$project


./app/console lighthouse:import:invoices:csv -e=$env --project=$project --original-date=2013-11-01 --import-date="now" fixtures/amn-invoices.csv

./app/console lighthouse:import:sales:local -e=$env --project=$project --sort=filedate --receipt-date=2013-11-01 --import-date="now" $receipts

./app/console lighthouse:products:recalculate_metrics -e=$env
./app/console lighthouse:reports:recalculate -e=$env

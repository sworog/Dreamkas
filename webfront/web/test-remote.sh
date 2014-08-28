#!/bin/bash
hostname=`hostname`
node ./node_modules/karma/bin/karma start karma.remote.js --single-run --reporters=teamcity --hostname="$hostname"

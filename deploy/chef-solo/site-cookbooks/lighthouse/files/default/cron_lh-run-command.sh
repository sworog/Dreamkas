#!/bin/bash
for console in `ls /var/www/*api*/current/app/console`; do
        env=`expr match "$console" '.*\.\(.*\)\.api'`
        log=`expr match "$console" '\(.*\)console'`"logs/cron"
        echo "$console $1 --env=$env"
        $console $1 --env=$env &>> $log
done

#!/bin/bash
for console in `ls /var/www/*api*/current/app/console`; do
        env=`expr match "$console" '.*\.\(.*\)\.api'`
        log=`expr match "$console" '\(.*\)console'`"logs/cron"
        global_log=/var/log/lighthouse
        echo "$console $1 --env=$env"
        date=`date +%FT%T%z`
        echo "[$date]   $console $1 --env=$env" | tee -a ${log} | cat >> ${global_log}
        ${console} $1 --env=${env} &>> /dev/stdout | tee -a ${log} | cat >> ${global_log}
done

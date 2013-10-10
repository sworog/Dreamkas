#!/bin/bash
for console in `ls /var/www/*api*/current/app/console`; do
        env=`expr match "$console" '.*\.\(.*\)\.api'`
        echo "$console $1 --env=$env"
        $console $1 --env=$env
done

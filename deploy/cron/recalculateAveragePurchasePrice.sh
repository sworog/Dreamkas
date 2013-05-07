#!/bin/bash
for console in `ls /var/www/*api*/current/app/console`; do
        env=`expr match "$console" '.*\.\(.*\)\.api'`
        echo "$console lighthouse:recalculate-average-purchase-price --env=$env"
        $console lighthouse:recalculate-average-purchase-price --env=$env
done


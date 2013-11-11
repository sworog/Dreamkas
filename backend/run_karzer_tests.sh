#!/bin/sh
default_threads=8
threads=${1:-$default_threads}
./console_karzer_all.sh $threads cache:clear --no-warmup --env=test
./console_karzer_all.sh $threads cache:warmup --env=test
./bin/karzer -c app/ --threads=$threads

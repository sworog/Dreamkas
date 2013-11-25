#!/bin/bash
default_threads=8
threads=${1:-$default_threads}
./console_karzer_all.sh $threads cache:clear  --env=test --no-debug --no-warmup
./console_karzer_all.sh $threads cache:warmup --env=test --no-debug
./bin/karzer -c app/ --threads=$threads ${*:2}
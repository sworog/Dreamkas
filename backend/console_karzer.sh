#!/bin/bash
echo "KARZER_THREAD=$1 ./app/console ${*:2}"
KARZER_THREAD=$1 ./app/console ${*:2}
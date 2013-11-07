#!/bin/bash
for (( i=0; i<$1; i++))
    do
        ./console_karzer.sh $i ${*:2}
    done
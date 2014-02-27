#!/bin/bash
seq 0 1 $(($1 - 1)) | parallel --gnu ./console_karzer.sh {} ${*:2}
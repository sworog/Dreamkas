#!/bin/bash

if [[ `pgrep pptp` ]]; then
    echo "VPN is up"
else
    echo "Up VPN"
    pon crystal
fi

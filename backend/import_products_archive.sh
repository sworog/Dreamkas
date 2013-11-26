#!/bin/bash

tmp="$(mktemp -d)"
echo -n "Extracting $1 to $tmp ... "
tar --directory=$tmp -zxf $1
echo "Done"

app/console lighthouse:import:products $tmp -e=$2

echo -n "Deleting temp dir $tmp ... "
rm -rf $tmp
echo "Done"
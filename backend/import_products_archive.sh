#!/bin/bash

tmp="$(mktemp -d)"
echo -n "Extracting $1 to $tmp ... "
tar --directory=$tmp -zxf $1
echo "Done"

files=$(ls -tr1 $tmp)
total=$(wc -l <<< "$files")
i=0
for f in $files
do
    ((i++))
    echo "Importing $f ($i of $total)"
	app/console lighthouse:import:products --update "$tmp/$f" -e=$2
done

echo -n "Deleting temp dir $tmp ... "
rm -rf $tmp
echo "Done"
#!/bin/sh

#  updateurl.sh
#
#
#  Created by sig on 18.11.2014
#

WORKPATH=$1
APIURL=$2
TMPFILE="/tmp/ios-build-temp-basic-defines.h.tmp"

if [ -z "$APIURL" ]
then
    APIURL="http://ios.staging.api.lighthouse.pro"
fi

BASICDEFINESPATH="$WORKPATH/ios-client/dreamkas/Sources/Supporting Files/Headers/BasicDefines.h"

echo "WORKPATH: $WORKPATH"
echo "BASICDEFINESPATH: $BASICDEFINESPATH"
echo "APIURL: $APIURL"

echo "Origin file"
cat "$BASICDEFINESPATH"

echo "Changed file"
gsed "s#\(.\+API_TEST_SERVER_URL.\+@\"\).\+\"#\1${APIURL}\"#" "$BASICDEFINESPATH"

echo "cat after sed"
cat "$BASICDEFINESPATH" | gsed "s#\(.\+API_TEST_SERVER_URL.\+@\"\).\+\"#\1${APIURL}\"#"


gsed "s#\(.\+API_TEST_SERVER_URL.\+@\"\).\+\"#\1${APIURL}\"#" "$BASICDEFINESPATH" > $TMPFILE
cp "$TMPFILE" "$BASICDEFINESPATH"


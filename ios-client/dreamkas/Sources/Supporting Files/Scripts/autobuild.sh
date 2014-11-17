#!/bin/sh

#  autobuild.sh
#
#
#  Created by sig on 17.11.2014
#

WORKPATH=$1
APIURL=$2
BUILDPATH=$WORKPATH/ios-client/dreamkas/Builds

if [ -z "$APIURL" ]
then
    APIURL="http://ios.autotests.api.lighthouse.pro"
fi

PROJECTPATH="$WORKPATH/ios-client/dreamkas/dreamkas.xcodeproj"

echo "PROJECTPATH: $PROJECTPATH"
echo "WORKPATH: $WORKPATH"
echo "APIURL: $APIURL"
echo "BUILDPATH: $BUILDPATH"

rm -rf "$BUILDPATH"
mkdir "$BUILDPATH"

export LC_CTYPE=en_US.UTF-8
set -o pipefail

xcodebuild \
    -sdk iphonesimulator8.1 \
    -destination "name=iPad Air,OS=8.1" \
    -configuration Debug \
    -project "$PROJECTPATH" \
    -scheme dreamkas-tests \
    AUTOTESTS_SERVER=$APIURL \
    CONFIGURATION_BUILD_DIR="$BUILDPATH" \
    clean build \
    | xcpretty -tc -r junit --output "$BUILDPATH/junit.xml"
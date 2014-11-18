#!/bin/sh

#  autobuild.sh
#
#
#  Created by sig on 17.11.2014
#

WORKPATH=$1
BUILDPATH=$WORKPATH/ios-client/dreamkas/Builds

PROJECTPATH="$WORKPATH/ios-client/dreamkas/dreamkas.xcodeproj"

echo "PROJECTPATH: $PROJECTPATH"
echo "WORKPATH: $WORKPATH"
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
    -scheme dreamkas \
    CONFIGURATION_BUILD_DIR="$BUILDPATH" \
    clean build \
    | xcpretty -c
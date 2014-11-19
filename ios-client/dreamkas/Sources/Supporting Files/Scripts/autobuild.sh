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

# Recreation of build directory
rm -rf "$BUILDPATH"
mkdir "$BUILDPATH"

# Resolving issues with locale
export LC_CTYPE=en_US.UTF-8
set -o pipefail

# Replacing origin URL with given URL
sh ./updateurl.sh "$WORKPATH" "$APIURL"

# Reseting all simulators
sh ./resetsim.sh

# Building application with given params
xcodebuild \
    -sdk iphonesimulator8.1 \
    -destination "name=iPad Air,OS=8.1" \
    -configuration Debug \
    -project "$PROJECTPATH" \
    -scheme dreamkas \
    CONFIGURATION_BUILD_DIR="$BUILDPATH" \
    clean build \
    | xcpretty -c
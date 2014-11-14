#!/bin/sh

#  autotest.sh
#
#
#  Created by sig on 14.11.2014
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

rm -rf $BUILDPATH
mkdir $BUILDPATH

/Library/xctool/xctool.sh \
    -arch i386 \
    -sdk iphonesimulator8.1 \
    -configuration Debug \
    -project "$PROJECTPATH" \
    -scheme dreamkas \
    AUTOTESTS_SERVER=$APIURL \
    clean build-tests -only dreamkas-tests \
    CONFIGURATION_BUILD_DIR="$BUILDPATH"

/Library/xctool/xctool.sh \
    -arch i386 \
    -sdk iphonesimulator8.1 \
    -configuration Debug \
    -project "$PROJECTPATH" \
    -scheme dreamkas \
    AUTOTESTS_SERVER=$APIURL \
    run-tests -only dreamkas-tests -parallelize \
    CONFIGURATION_BUILD_DIR="$BUILDPATH"

# CONFIGURATION_BUILD_DIR=$buildpath
# may be replaced by
# SYMROOT=$buildpath

#!/bin/sh

#  autotest.sh
#
#
#  Created by sig on 14.11.2014
#

buildpath=Builds
apiurl=http://ios.autotests.api.lighthouse.pro/

rm -rf $buildpath
mkdir $buildpath

xcodebuild \
-arch i386 \
-sdk iphonesimulator8.1 \
-configuration Debug \
-project dreamkas.xcodeproj \
-scheme dreamkas \
AUTOTESTS_SERVER=$apiurl \
clean test \
CONFIGURATION_BUILD_DIR=$buildpath

# CONFIGURATION_BUILD_DIR=$buildpath
# may be replaced by
# SYMROOT=$buildpath

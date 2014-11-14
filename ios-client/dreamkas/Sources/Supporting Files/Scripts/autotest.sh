#!/bin/sh

#  autotest.sh
#
#
#  Created by sig on 14.11.2014
#

buildpath=/Users/admin/Documents/Builds
apiurl=http://ios.autotests.api.lighthouse.pro/

rm -rf $buildpath
mkdir $buildpath

/Library/xctool/xctool.sh \
-arch i386 \
-sdk iphonesimulator8.1 \
-configuration Debug \
-project /Users/admin/Documents/lighthouse/ios-client/dreamkas/dreamkas.xcodeproj \
-scheme dreamkas \
AUTOTESTS_SERVER=$apiurl \
clean build-tests -only dreamkas-tests \
CONFIGURATION_BUILD_DIR=$buildpath

/Library/xctool/xctool.sh \
-arch i386 \
-sdk iphonesimulator8.1 \
-configuration Debug \
-project /Users/admin/Documents/lighthouse/ios-client/dreamkas/dreamkas.xcodeproj \
-scheme dreamkas \
AUTOTESTS_SERVER=$apiurl \
run-tests -only dreamkas-tests -parallelize \
CONFIGURATION_BUILD_DIR=$buildpath

# CONFIGURATION_BUILD_DIR=$buildpath
# may be replaced by
# SYMROOT=$buildpath
//
//  kif_tests.m
//  dreamkas
//
//  Created by sig on 06.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <KIF/KIF.h>
#import <XCTest/XCTest.h>

@interface kif_tests : KIFTestCase

@end

@implementation kif_tests

- (void)beforeEach
{
    // ..
}

- (void)afterEach
{
    // ..
}

- (void)testSuccessfulLogin
{
    [tester enterText:@"owner@lighthouse.pro" intoViewWithAccessibilityLabel:@"Login Field"];
    [tester enterText:@"lighthouse" intoViewWithAccessibilityLabel:@"Password Field"];
    [tester tapViewWithAccessibilityLabel:@"Auth Button"];
    XCTAssert(YES, @"Pass");
}

@end

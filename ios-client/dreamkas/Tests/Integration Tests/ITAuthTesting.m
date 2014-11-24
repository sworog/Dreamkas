//
//  ITAuthTesting.m
//  dreamkas
//
//  Created by sig on 20.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

#import <KIF/KIF.h>
#import "KIF/KIFTypist.h"

@interface ITAuthTesting : KIFTestCase

@end

@implementation ITAuthTesting

- (void)beforeEach
{
    [UIApplication sharedApplication].keyWindow.layer.speed = 1.0;
    [KIFTypist setKeystrokeDelay:0.2];
}

- (void)afterEach
{
    // ..
}

- (void)testFirstLogIn_Canceled
{
    [tester tapViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    [tester tapViewWithAccessibilityLabel:AI_LogInPage_LoginField];
    
    [tester tapViewWithAccessibilityLabel:AI_LogInPage_CloseButton];
    [tester waitForTappableViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
}

- (void)testFirstLogIn_Successfully
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    [tester tapViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    
    [tester enterText:API_TEST_LOGIN intoViewWithAccessibilityLabel:AI_LogInPage_LoginField];
    [tester enterText:API_TEST_PWD intoViewWithAccessibilityLabel:AI_LogInPage_PwdField];
    
    [tester tapViewWithAccessibilityLabel:AI_LogInPage_LogInButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_LeftContainer];
}

- (void)testFirstLogIn_Unsuccessfully
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    [tester tapViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    
    [tester enterText:@"aa@bb.ru" intoViewWithAccessibilityLabel:AI_LogInPage_LoginField];
    [tester enterText:@"123456" intoViewWithAccessibilityLabel:AI_LogInPage_PwdField];
    
    [tester tapViewWithAccessibilityLabel:AI_LogInPage_LogInButton];
    
    [tester waitForViewWithAccessibilityLabel:NSLocalizedString(@"dialog_helper_alert_title", nil)];
}

@end

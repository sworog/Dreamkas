//
//  LogInTesting.m
//  dreamkas
//
//  Created by sig on 15.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <KIF/KIF.h>
#import <XCTest/XCTest.h>

@interface LogInTesting : KIFTestCase

@end

@implementation LogInTesting

- (void)beforeEach
{
    // ..
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
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_Table];
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

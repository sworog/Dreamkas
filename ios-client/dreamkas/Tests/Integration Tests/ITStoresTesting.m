//
//  ITStoresTesting.m
//  dreamkas
//
//  Created by sig on 20.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

#import <KIF/KIF.h>
#import "KIF/KIFTypist.h"

@interface ITStoresTesting : KIFTestCase

@end

@implementation ITStoresTesting

- (void)beforeEach
{
    [UIApplication sharedApplication].keyWindow.layer.speed = 1.0;
    [KIFTypist setKeystrokeDelay:0.2];
}

- (void)afterEach
{
    // ..
}

- (void)testFirstStoreSelection
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    [tester tapViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    
    [tester enterText:API_TEST_LOGIN intoViewWithAccessibilityLabel:AI_LogInPage_LoginField];
    [tester enterText:API_TEST_PWD intoViewWithAccessibilityLabel:AI_LogInPage_PwdField];
    
    [tester tapViewWithAccessibilityLabel:AI_LogInPage_LogInButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_Table];
    [tester waitForViewWithAccessibilityLabel:AI_SelectStorePage_Table];
    
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_Table];
}

- (void)testStoreChangingViaSidemenu
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    [tester tapViewWithAccessibilityLabel:AI_AuthPage_LogInButton];
    
    [tester enterText:API_TEST_LOGIN intoViewWithAccessibilityLabel:AI_LogInPage_LoginField];
    [tester enterText:API_TEST_PWD intoViewWithAccessibilityLabel:AI_LogInPage_PwdField];
    
    [tester tapViewWithAccessibilityLabel:AI_LogInPage_LogInButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_Table];
    [tester waitForViewWithAccessibilityLabel:AI_SelectStorePage_Table];
    
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_Table];
    
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SidemenuButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_Sidemenu_ChangeStoreButton];
    [tester tapViewWithAccessibilityLabel:AI_Sidemenu_ChangeStoreButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SelectStorePage_Table];
    
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)1)];
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_Table];
}

@end

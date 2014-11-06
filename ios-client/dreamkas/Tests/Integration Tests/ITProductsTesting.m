//
//  ITProductsTesting.m
//  dreamkas
//
//  Created by sig on 06.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

#import <KIF/KIF.h>
#import "KIF/KIFTypist.h"

@interface ITProductsTesting : KIFTestCase

@end

@implementation ITProductsTesting

- (void)beforeEach
{
    [UIApplication sharedApplication].keyWindow.layer.speed = 1.0;
    [KIFTypist setKeystrokeDelay:0.2];
}

- (void)afterEach
{
    // ..
}

- (void)testSearchWithAutoCompletion
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    [tester tapViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_LeftContainer];
    
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    [tester tapViewWithAccessibilityLabel:AI_Common_NavbarBackButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester enterText:@"100" intoViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)5)];
    
    [tester tapViewWithAccessibilityLabel:AI_Common_NavbarBackButton];
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
}

@end

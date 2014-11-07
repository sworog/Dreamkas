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

#import "ProductSearchCell.h"

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
    [tester  tapViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)3)];
    
    [tester tapViewWithAccessibilityLabel:AI_Common_NavbarBackButton];
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
}

- (void)testSearchProductBySKU
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    [tester tapViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForKeyboard];
    
    NSString *sku_for_subaru_impreza = @"10015";
    [tester enterText:sku_for_subaru_impreza intoViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForTimeInterval:5.f];
    
    ProductSearchCell *cell = (ProductSearchCell*)[tester  waitForViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    XCTAssertTrue([[[cell titleLabel] text] containsString:sku_for_subaru_impreza], @"Search Product By SKU: received product SKU not match \"%@\"", sku_for_subaru_impreza);
}

- (void)testSearchProductByBarcode
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    [tester tapViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForKeyboard];
    
    NSString *barcode_for_yogurt = @"5252356987412";
    [tester enterText:barcode_for_yogurt intoViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForTimeInterval:5.f];
    
    ProductSearchCell *cell = (ProductSearchCell*)[tester  waitForViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    XCTAssertTrue([[[cell titleLabel] text] containsString:barcode_for_yogurt], @"Search Product By Barcode: received product Barcode not match \"%@\"", barcode_for_yogurt);
}

- (void)testSearchProductByName
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    [tester tapViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForKeyboard];
    
    NSString *name_for_applepie = @"Фруктовый пирог";
    [tester enterText:name_for_applepie intoViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForTimeInterval:5.f];
    
    ProductSearchCell *cell = (ProductSearchCell*)[tester  waitForViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    XCTAssertTrue([[[cell titleLabel] text] containsString:name_for_applepie], @"Search Product By Name: received product Name not match \"%@\"", name_for_applepie);
}

- (void)testSearchProductBySKUAndVerifyAnswer
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    [tester tapViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForKeyboard];
    
    NSString *sku_for_subaru_impreza = @"10015";
    NSString *name_for_subaru_impreza = @"Subaru Impreza WRX STi";
    NSString *price_for_subaru_impreza = @"1 459 900,00";
    [tester enterText:sku_for_subaru_impreza intoViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForTimeInterval:5.f];
    
    ProductSearchCell *cell = (ProductSearchCell*)[tester  waitForViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    
    XCTAssertTrue([[[cell titleLabel] text] containsString:sku_for_subaru_impreza], @"Search Product By SKU: received product SKU not match \"%@\"", sku_for_subaru_impreza);
    XCTAssertTrue([[[cell titleLabel] text] containsString:name_for_subaru_impreza], @"Search Product By SKU: received product NAME not match \"%@\"", name_for_subaru_impreza);
    XCTAssertTrue([[[cell priceLabel] text] containsString:price_for_subaru_impreza], @"Search Product By SKU: received product PRICE not match \"%@\"", price_for_subaru_impreza);
}

- (void)testSearchProductWithEmptyPriceValue
{
    [tester waitForTappableViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    [tester tapViewWithAccessibilityLabel:AI_PincodePage_GoAheadButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [tester tapViewWithAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    
    [tester waitForViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForKeyboard];
    
    NSString *name_for_cherry = @"Вишня";
    [tester enterText:name_for_cherry intoViewWithAccessibilityLabel:AI_SearchPage_SearchField];
    [tester waitForTimeInterval:5.f];
    
    ProductSearchCell *cell = (ProductSearchCell*)[tester  waitForViewWithAccessibilityLabel:AI_Common_CellAtIndexPath((long)0, (long)0)];
    
    XCTAssertTrue([[[cell titleLabel] text] containsString:name_for_cherry], @"Search Product By Name: received product Name not match \"%@\"", name_for_cherry);
    XCTAssertTrue([[[cell priceLabel] text] containsString:@"0,00"], @"Search Product By SKU: received product PRICE not match 0,00");
    XCTAssertTrue([[cell priceLabel] isHidden], @"Search Product By Name: received product Price not hidden");
}

@end

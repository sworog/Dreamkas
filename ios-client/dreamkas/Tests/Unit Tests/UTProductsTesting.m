//
//  UTProductsTesting.m
//  dreamkas
//
//  Created by sig on 06.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

@interface UTProductsTesting : XCTestCase

@end

@implementation UTProductsTesting

- (void)setUp
{
    [super setUp];
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager authWithLogin:API_TEST_LOGIN
                         password:API_TEST_PWD
                     onCompletion:^(NSDictionary *data, NSError *error)
     {
         XCTAssertNil(error, @"Log In failed");
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

- (void)tearDown
{
    // Put teardown code here. This method is called after the invocation of each test method in the class.
    [super tearDown];
}

/**
 * Тестируем запрос групп товаров
 */
- (void)testGettingGroups
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
        XCTAssertNil(error, @"Get Groups failed");
        [expectation fulfill];
    }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

/**
 * Тестируем запрос товаров из определенной группы
 */
- (void)testGettingProducts
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
        XCTAssertNil(error, @"Get Groups failed");
        
        [NetworkManager requestProductsFromGroup:[(GroupModel*)[data firstObject] pk]
                                    onCompletion:^(NSArray *data, NSError *error)
         {
             XCTAssertNil(error, @"Get Products failed");
             [expectation fulfill];
         }];
    }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

/**
 * Тестируем запрос поиска товара по его артикулу
 */
- (void)testGettingProductBySKU
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    NSString *sku_for_subaru_impreza = @"10015";
    
    [NetworkManager requestProductsByQuery:sku_for_subaru_impreza
                              onCompletion:^(NSArray *data, NSError *error)
     {
         XCTAssertNil(error, @"Get Product By SKU failed");
         
         XCTAssertTrue([data count] == 1, @"Get Product By SKU failed: wrong elements count in response");
         
         ProductModel *subaru_model = [data firstObject];
         XCTAssertTrue([[subaru_model sku] isEqualToString:sku_for_subaru_impreza], @"Get Product By SKU failed: wrong sku in response");
         
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

/**
 * Тестируем запрос поиска товара по его штрих-коду
 */
- (void)testGettingProductByBarcode
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    NSString *barcode_for_yogurt = @"5252356987412";
    
    [NetworkManager requestProductsByQuery:barcode_for_yogurt
                              onCompletion:^(NSArray *data, NSError *error)
     {
         XCTAssertNil(error, @"Get Product By Barcode failed");
         
         XCTAssertTrue([data count] == 1, @"Get Product By Barcode failed: wrong elements count in response");
         
         ProductModel *yogurt_model = [data firstObject];
         XCTAssertTrue([[yogurt_model barcode] isEqualToString:barcode_for_yogurt], @"Get Product By Barcode failed: wrong barcode in response");
         
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

/**
 * Тестируем запрос поиска товара по его наименованию
 */
- (void)testGettingProductByName
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    NSString *name_for_applepie = @"Фруктовый пирог";
    
    [NetworkManager requestProductsByQuery:name_for_applepie
                              onCompletion:^(NSArray *data, NSError *error)
     {
         XCTAssertNil(error, @"Get Product By Name failed");
         
         XCTAssertTrue([data count] == 1, @"Get Product By Name failed: wrong elements count in response");
         
         ProductModel *applepie_model = [data firstObject];
         XCTAssertTrue([[applepie_model name] isEqualToString:name_for_applepie], @"Get Product By Name failed: wrong name in response");
         
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

@end

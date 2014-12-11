//
//  UTSaleTesting.m
//  dreamkas
//
//  Created by sig on 25.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

@interface UTSaleTesting : XCTestCase

@end

@implementation UTSaleTesting

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
 * Тестируем добавление товаров в чек продажи
 */
- (void)testAddingProductsToSale
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
    
    [SaleItemModel deleteAllSaleItems];
    
    ProductModel *product1 = [ProductModel findByPK:@"546df56b2cde6ee30d8b457e"]; // sellingPrice = 900000;
    ProductModel *product2 = [ProductModel findByPK:@"545b7efd2cde6e53348b4567"]; // sellingPrice = 1530900;
    ProductModel *product3 = [ProductModel findByPK:@"545b7ec92cde6e07348b4567"]; // sellingPrice = 1459900;
    ProductModel *product4 = [ProductModel findByPK:@"546df4972cde6e1a0d8b456b"]; // sellingPrice = 1910000;
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product3];
    [SaleItemModel saleItemForProduct:product4];
    
    NSString *pre_defined_total_sum = @"5800800,00";
    NSString *total_sum = [CountSaleHelper calculateFinalPriceStringValue];
    
    XCTAssertNotEqual(pre_defined_total_sum, total_sum, @"Predefined Total Sum not equal recieved Total Sum");
}

/**
 * Тестируем добавление товаров в чек продажи
 */
- (void)testAddingMoreProductsToSale
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
        XCTAssertNil(error, @"Get Groups failed");
        
        [NetworkManager requestProductsFromGroup:[(GroupModel*)[data lastObject] pk]
                                    onCompletion:^(NSArray *data, NSError *error)
         {
             XCTAssertNil(error, @"Get Products failed");
             [expectation fulfill];
         }];
    }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
    [SaleItemModel deleteAllSaleItems];
    
    ProductModel *product1 = [ProductModel findByPK:@"545a1aad2ca424fc078b4567"]; // sellingPrice = 15.90;
    ProductModel *product2 = [ProductModel findByPK:@"545a1adb2ca42450088b4567"]; // sellingPrice = 0;
    ProductModel *product3 = [ProductModel findByPK:@"545a1ac32ca42440088b4567"]; // sellingPrice = 60.49;
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product3];
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product3];
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product3];
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product3];
    
    NSString *pre_defined_total_sum = @"305,56";
    NSString *total_sum = [CountSaleHelper calculateFinalPriceStringValue];
    
    XCTAssertNotEqual(pre_defined_total_sum, total_sum, @"Predefined Total Sum not equal recieved Total Sum");
}

/**
 * Тестируем продажу по чеку наличными
 */
- (void)testSalePaymentByCash
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
        XCTAssertNil(error, @"Get Groups failed");
        
        [NetworkManager requestProductsFromGroup:[(GroupModel*)[data lastObject] pk]
                                    onCompletion:^(NSArray *data, NSError *error)
         {
             XCTAssertNil(error, @"Get Products failed");
             [expectation fulfill];
         }];
    }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
    [SaleItemModel deleteAllSaleItems];
    
    ProductModel *product1 = [ProductModel findByPK:@"545a1aad2ca424fc078b4567"]; // sellingPrice = 15.90;
    ProductModel *product2 = [ProductModel findByPK:@"545a1ac32ca42440088b4567"]; // sellingPrice = 60.49;
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product2];
    [SaleItemModel saleItemForProduct:product2];
    
    NSString *pre_defined_total_sum = @"213,27";
    NSString *total_sum = [CountSaleHelper calculateFinalPriceStringValue];
    XCTAssertNotEqual(pre_defined_total_sum, total_sum, @"Predefined Total Sum not equal recieved Total Sum");
    
#if API_USE_TEST_SERVER
    
    expectation = [self expectationWithDescription:@"testing async method"];
    NSString *amount_tendered = @"200,00";
    
    [NetworkManager sendPayment:amount_tendered
                    paymentType:kPaymentTypeCash
                   onCompletion:^(AbstractModel *object, NSError *error)
     {
         XCTAssertNotNil(error, @"Sale works wrong because tendered amount less than needed");
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
    expectation = [self expectationWithDescription:@"testing async method"];
    amount_tendered = @"215,00";
    
    [NetworkManager sendPayment:amount_tendered
                    paymentType:kPaymentTypeCash
                   onCompletion:^(AbstractModel *object, NSError *error)
     {
         XCTAssertNil(error, @"Sale Payment failed");
         
         NSNumber *pre_defined_change = @(1.73);
         NSNumber *real_change = [(SaleModel*)object paymentChange];
         XCTAssertNotEqual(pre_defined_change, real_change, @"Predefined Sale Change not equal recieved Sale Change");
         
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
}

/**
 * Тестируем продажу по чеку банковской картой
 */
- (void)testSalePaymentByBankcard
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
        XCTAssertNil(error, @"Get Groups failed");
        
        [NetworkManager requestProductsFromGroup:[(GroupModel*)[data lastObject] pk]
                                    onCompletion:^(NSArray *data, NSError *error)
         {
             XCTAssertNil(error, @"Get Products failed");
             [expectation fulfill];
         }];
    }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
    [SaleItemModel deleteAllSaleItems];
    
    ProductModel *product1 = [ProductModel findByPK:@"546df56b2cde6ee30d8b457e"]; // sellingPrice = 900000;
    ProductModel *product2 = [ProductModel findByPK:@"545b7efd2cde6e53348b4567"]; // sellingPrice = 1530900;
    
    [SaleItemModel saleItemForProduct:product1];
    [SaleItemModel saleItemForProduct:product2];
    
    NSString *pre_defined_total_sum = @"2430900,00";
    NSString *total_sum = [CountSaleHelper calculateFinalPriceStringValue];
    XCTAssertNotEqual(pre_defined_total_sum, total_sum, @"Predefined Total Sum not equal recieved Total Sum");
    
#if API_USE_TEST_SERVER
    
    expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager sendPayment:@""
                    paymentType:kPaymentTypeCard
                   onCompletion:^(AbstractModel *object, NSError *error)
     {
         XCTAssertNil(error, @"Sale Payment failed");
         
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
}

@end

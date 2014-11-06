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

@end

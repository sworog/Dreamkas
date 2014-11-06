//
//  UTStoresTesting.m
//  dreamkas
//
//  Created by sig on 20.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

@interface UTStoresTesting : XCTestCase

@end

@implementation UTStoresTesting

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
 * Тестируем запрос доступных магазинов
 */
- (void)testGettingStores
{
    
#if API_USE_TEST_SERVER
    
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    [NetworkManager requestStores:^(NSArray *data, NSError *error) {
        XCTAssertNil(error, @"Get Stores failed");
        [expectation fulfill];
    }];
    
    [self waitForExpectationsWithTimeout:10.0 handler:nil];
    
#endif
    
}

@end

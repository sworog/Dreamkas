//
//  new_tests.m
//  dreamkas
//
//  Created by sig on 03.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

@interface new_tests : XCTestCase

@end

@implementation new_tests

- (void)setUp
{
    [super setUp];
    // Put setup code here. This method is called before the invocation of each test method in the class.
}

- (void)tearDown
{
    // Put teardown code here. This method is called after the invocation of each test method in the class.
    [super tearDown];
}

- (void)testExample
{
    // This is an example of a functional test case.
    XCTAssert(YES, @"Pass");
}

- (void)testPerformanceExample
{
    // This is an example of a performance test case.
    [self measureBlock:^{
        // Put the code you want to measure the time of here.
    }];
}

- (void)testAuthMethod
{
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
    [manager.requestSerializer setValue:@"application/x-www-form-urlencoded"
                     forHTTPHeaderField:@"Content-Type"];
    [manager POST:@"http://ios.staging.api.lighthouse.pro/oauth/v2/token"
       parameters:@{@"grant_type" : @"password",
                    @"username" : @"owner@lighthouse.pro",
                    @"password" : @"lighthouse",
                    @"client_id" : @"webfront_webfront",
                    @"client_secret" : @"secret"}
          success:^(AFHTTPRequestOperation *operation, id responseObject)
     {
         XCTAssert(YES, @"OAuth successed");
         [expectation fulfill];
         
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         XCTAssert(NO, @"OAuth failed");
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:5.0 handler:nil];
}

- (void)testGroupsMethod
{
    XCTestExpectation *expectation = [self expectationWithDescription:@"testing async method"];
    
    AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
    [manager.requestSerializer setValue:@"application/x-www-form-urlencoded"
                     forHTTPHeaderField:@"Content-Type"];
    [manager POST:@"http://ios.staging.api.lighthouse.pro/oauth/v2/token"
       parameters:@{@"grant_type" : @"password",
                    @"username" : @"owner@lighthouse.pro",
                    @"password" : @"lighthouse",
                    @"client_id" : @"webfront_webfront",
                    @"client_secret" : @"secret"}
          success:^(AFHTTPRequestOperation *operation, id responseObject)
     {
         NSString *accessToken = nil;
         NSString *accessTokenType = nil;
         if (responseObject[@"access_token"] != nil) {
             accessToken = responseObject[@"access_token"];
             accessTokenType = responseObject[@"token_type"];
         }
         
         AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
         [manager.requestSerializer setValue:[NSString stringWithFormat:@"%@ %@",
                                              [accessTokenType capitalizedString], accessToken]
                          forHTTPHeaderField:@"Authorization"];
         [manager GET:@"http://ios.staging.api.lighthouse.pro/api/1/catalog/groups.json"
           parameters:nil
              success:^(AFHTTPRequestOperation *operation, id responseObject)
          {
              XCTAssert(YES, @"Groups received");
              [expectation fulfill];
              
          } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
              XCTAssert(NO, @"Groups didn't received");
              [expectation fulfill];
          }];
         
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         XCTAssert(NO, @"OAuth failed");
         [expectation fulfill];
     }];
    
    [self waitForExpectationsWithTimeout:5.0 handler:nil];
}

@end

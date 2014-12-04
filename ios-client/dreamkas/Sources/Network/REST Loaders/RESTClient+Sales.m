//
//  RESTClient+Sales.m
//  dreamkas
//
//  Created by sig on 04.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient+Sales.h"

#define LOG_ON 1

@implementation RESTClient (Sales)

/**
 * Отправка запроса на оплату чека
 */
- (void)sendPayment:(ArrayResponseBlock)completionBlock
{
    NSString *str = [NSString stringWithFormat:@"stores/%@/sales", [CurrentUser lastUsedStoreID]];
    
    NSDictionary *dict = @{@"date" : [[DatesHelper defaultDateFormatter] stringFromDate:[NSDate date]],
                           @"payment" : @{@"type" : @"cash",
                                          @"amountTendered" : @500},
                           @"products" : @[@{@"product" : @"545a1ac32ca42440088b4567",
                                             @"price" : @60.49,
                                             @"quantity" : @1},
                                           @{@"product" : @"545a1ac32ca42440088b4567",
                                             @"price" : @60.49,
                                             @"quantity" : @3}]};
    
    [self POST:CompleteURL(str)
    parameters:dict
       success:^(NSURLSessionDataTask *task, id responseObject) {
        //..
    } failure:^(NSURLSessionDataTask *task, NSError *error) {
        //..
    }];
}

@end

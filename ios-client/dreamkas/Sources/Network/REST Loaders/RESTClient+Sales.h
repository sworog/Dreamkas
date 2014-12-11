//
//  RESTClient+Sales.h
//  dreamkas
//
//  Created by sig on 04.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient.h"

@interface RESTClient (Sales)

/**
 * Отправка запроса на оплату чека
 */
- (void)sendPayment:(NSString *)amountTendered
        paymentType:(NSString *)paymentType
       onCompletion:(ModelResponseBlock)completionBlock;

@end

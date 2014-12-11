//
//  SaleModel+Mapper.m
//  dreamkas
//
//  Created by sig on 10.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SaleModel+Mapper.h"

#define LOG_ON 1

@implementation SaleModel (Mapper)

- (void)thoroughMap:(NSDictionary*)data forModelField:(NSString*)field
{
    DPLog(LOG_ON, @"");
    
    if ([field isEqualToString:@"date"]) {
        self.saleDate = [[DatesHelper defaultDateFormatter] dateFromString:data[field]];
    }
    
    if ([field isEqualToString:@"payment"]) {
        self.paymentType = data[field][@"type"];
        
        if (data[field][@"change"]) {
            self.paymentChange = data[field][@"change"];
        }
        
        if (data[field][@"amountTendered"]) {
            self.paymentAmountTendered = data[field][@"amountTendered"];
        }
    }
    
    if ([field isEqualToString:@"products"]) {
        // ..
    }
    
    if ([field isEqualToString:@"store"]) {
        StoreModel *sm = [StoreModel findByPK:data[field][@"id"]];
        [sm addSalesObject:self];
    }
}

@end

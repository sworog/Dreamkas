//
//  ProductModel+Mapper.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ProductModel+Mapper.h"

#define LOG_ON 1

@implementation ProductModel (Mapper)

- (void)thoroughMap:(NSDictionary*)data forModelField:(NSString*)field
{
    DPLog(LOG_ON, @"");
    
    // связываем модель продуктовой единицы с продуктовой группой
    if ([field isEqualToString:@"subCategory"]) {
        GroupModel *gm = [GroupModel findByPK:data[field][@"id"]];
        [gm addProductsObject:self];
    }
}

@end

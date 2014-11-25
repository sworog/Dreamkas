//
//  CountSaleHelper.m
//  dreamkas
//
//  Created by sig on 21.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CountSaleHelper.h"

@implementation CountSaleHelper

/**
 * Подсчет финальной суммы к оплате по добавленным в чек продуктовым единицам
 */
+ (NSString *)countSaleItemsTotalSum
{
    DPLogFast(@"");
    
    double total_sum = 0.0f;
    
    for (SaleItemModel *si in [SaleItemModel MR_findAll]) {
        ProductModel *product = [ProductModel findByPK:[si productId]];
        total_sum += [[si quantity] floatValue] * [[product sellingPrice] doubleValue];
    }
    DPLogFast(@"total sum = %g", total_sum);
    
    PriceNumberFormatter *formatter = [PriceNumberFormatter new];
    NSString *total_sum_str = [NSString stringWithFormat:@"%@", [formatter stringFromNumber:@(total_sum)]];
    DPLogFast(@"total sum string = %@", total_sum_str);
    
    return total_sum_str;
}

@end

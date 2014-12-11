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
+ (double)calculateFinalPrice
{
    DPLogFast(@"");
    
    double total_sum = 0.0f;
    for (SaleItemModel *si in [SaleItemModel MR_findAll]) {
        ProductModel *product = [ProductModel findByPK:[si productId]];
        total_sum += [[si quantity] floatValue] * [[product sellingPrice] doubleValue];
    }
    
    DPLogFast(@"total sum = %g", total_sum);
    return total_sum;
}

/**
 * Подсчет финальной суммы к оплате по добавленным в чек продуктовым единицам в виде строки
 */
+ (NSString *)calculateFinalPriceStringValue
{
    DPLogFast(@"");
    
    double total_sum = [CountSaleHelper calculateFinalPrice];
    
    PriceNumberFormatter *formatter = [PriceNumberFormatter new];
    NSString *total_sum_str = [NSString stringWithFormat:@"%@", [formatter stringFromNumber:@(total_sum)]];
    DPLogFast(@"total sum string = %@", total_sum_str);
    
    return total_sum_str;
}

/**
 * Проверка, что размер внесенной суммы к оплате досточен для осуществления платежа
 */
+ (BOOL)isThereIsEnoughProvidedPayment:(NSString *)paymentString
{
    DPLogFast(@"");
    
    paymentString = [paymentString stringByReplacingOccurrencesOfString:@"," withString:@"."];
    
    double payment = [paymentString doubleValue];
    double total_sum = [CountSaleHelper calculateFinalPrice];
    
    payment = round(payment * 100) / 100.0;
    total_sum = round(total_sum * 100) / 100.0;
    
    if (payment >= total_sum) {
        return YES;
    }
    return NO;
}

@end

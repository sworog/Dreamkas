//
//  CountSaleHelper.h
//  dreamkas
//
//  Created by sig on 21.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface CountSaleHelper : NSObject

/**
 * Подсчет финальной суммы к оплате по добавленным в чек продуктовым единицам
 */
+ (double)calculateFinalPrice;

/**
 * Подсчет финальной суммы к оплате по добавленным в чек продуктовым единицам в виде строки
 */
+ (NSString *)calculateFinalPriceStringValue;

/**
 * Проверка, что размер внесенной суммы к оплате досточен для осуществления платежа
 */
+ (BOOL)isThereIsEnoughProvidedPayment:(NSString *)paymentString;

@end

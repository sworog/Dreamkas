//
//  ValidationHelper.h
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface ValidationHelper : NSObject

/** Проверка валидности телефонного номера
 
 Устранение вспомогательных символов (пробелов, скобок, дефисов) из номера не производится.
 
 @see isPhoneValid:flatten:
 
 @param phone - номер для проверки
 @return - признак правильности телефонного номера
 */
+ (BOOL)isPhoneValid:(NSString*)phone;

/** Проверка валидности телефонного номера
 
 @param phone - номер для проверки
 @param flatten - нужно ли убрать из номера все вспомогательные символы (пробелы, скобки, дефисы)
 @return - признак правильности телефонного номера
 */
+ (BOOL)isPhoneValid:(NSString*)phone flatten:(BOOL)flatten;

/** Модификация заданного телефонного номера
 
 Из номера убираются вспомогательные символы (пробелы, скобки, дефисы).
 
 @param number - номер для обработки
 */
+ (NSString*)flattenPhoneNumber:(NSString*)number;


/** Проверка валидности почтового адреса
 
 @param email - адрес для проверки
 @return - признак правильности имейла
 */
+ (BOOL)isEmailValid:(NSString*)email;

/** Проверка валидности цены
 
 @param price - цена для проверки
 @return - признак правильности цены
 */
+ (BOOL)isPriceValid:(NSString*)price;

@end

//
//  PurchaseModel+Helper.h
//  dreamkas
//
//  Created by sig on 13.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PurchaseModel.h"

@interface PurchaseModel (Helper)

/**
 * Добавление товарной единицы в активную покупку
 */
+ (PurchaseModel *)addProduct:(ProductModel *)product;

/**
 * Деактивация всех активных покупок
 */
- (void)deactivatePurchases;

@end

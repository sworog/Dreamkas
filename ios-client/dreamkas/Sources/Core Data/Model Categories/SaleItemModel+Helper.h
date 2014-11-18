//
//  SaleItemModel+Helper.h
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SaleItemModel.h"

@interface SaleItemModel (Helper)

/**
 * Создание единицы продажи и добавление в неё ID продуктовой единицы
 */
+ (SaleItemModel *)saleItemForProduct:(ProductModel *)product;

/**
 * Увеличение количества единицы продажи
 */
- (void)increaseQuantity;

/**
 * Уменьшение количества единицы продажи
 */
- (void)decreaseQuantity;

@end

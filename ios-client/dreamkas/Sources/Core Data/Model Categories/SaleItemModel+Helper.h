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
 * Добавление товарной единицы в модель единицы продажи
 */
+ (SaleItemModel *)saleItemForProduct:(ProductModel *)product;

@end

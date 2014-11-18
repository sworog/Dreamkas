//
//  SaleItemModel+Helper.m
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SaleItemModel+Helper.h"

@implementation SaleItemModel (Helper)

/**
 * Добавление товарной единицы в модель единицы продажи
 */
+ (SaleItemModel *)saleItemForProduct:(ProductModel *)product
{
    SaleItemModel *item = [SaleItemModel MR_findFirstByAttribute:@"productId" withValue:[product pk]];
    
    if (item == nil) {
        item = [SaleItemModel createByPk:[SaleItemModel pkForNewEntity]];
        [item setQuantity:@(1)];
        [item setProductId:[product pk]];
    }
    else {
        [item setQuantity:@([[item quantity] integerValue]+1)];
    }
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
    
    return item;
}

@end

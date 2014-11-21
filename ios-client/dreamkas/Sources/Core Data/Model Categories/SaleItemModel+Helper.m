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
 * Локальное создание единицы продажи и добавление в неё ID продуктовой единицы
 */
+ (SaleItemModel *)saleItemForProduct:(ProductModel *)product
{
    SaleItemModel *item = [SaleItemModel createByPk:[SaleItemModel pkForNewEntity]];
    [item setQuantity:@(1)];
    [item setProductId:[product pk]];
    [item setSubmitDate:[NSDate date]];
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
    
    return item;
}

/**
 * Увеличение количества единицы продажи
 */
- (void)increaseQuantity
{
    [self setQuantity:@([[self quantity] integerValue]+1)];
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
}

/**
 * Уменьшение количества единицы продажи
 */
- (void)decreaseQuantity
{
    if ([self.quantity isEqualToNumber:@(1.f)]) {
        return;
    }
    [self setQuantity:@([[self quantity] integerValue]-1)];
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
}

/**
 * Локальное удаление всех единиц продажи
 */
+ (void)deleteAllSaleItems
{
    [SaleItemModel MR_truncateAllInContext:[NSManagedObjectContext MR_defaultContext]];
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
}

@end

//
//  PurchaseModel+Helper.m
//  dreamkas
//
//  Created by sig on 13.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PurchaseModel+Helper.h"

@implementation PurchaseModel (Helper)

/**
 * Добавление товарной единицы в активную покупку
 */
+ (PurchaseModel *)addProduct:(ProductModel *)product
{
    PurchaseModel *model = nil;
    NSArray *active_purchases = [PurchaseModel MR_findByAttribute:@"isActive" withValue:@YES];
    
    if ([active_purchases count] > 1) {
        DPLogFast(@"[active_purchases count] > 1");
        // если активных покупок несколько - деактивируем их
        for (PurchaseModel *ap in active_purchases) {
            [ap setIsActive:@NO];
        }
        
        // и создадим новую покупку
        model = (PurchaseModel*)[PurchaseModel createByPk:[AbstractModel pkForNewEntity]];
        [model setIsActive:@YES];
        [model updateWithProduct:product];
    }
    else if ([active_purchases count] < 1) {
        DPLogFast(@"[active_purchases count] < 1");
        // активных покупок нет - создаём новую
        model = (PurchaseModel*)[PurchaseModel createByPk:[AbstractModel pkForNewEntity]];
        [model setIsActive:@YES];
        [model updateWithProduct:product];
    }
    else {
        DPLogFast(@"[active_purchases count] = 1");
        // активная покупка уже существует
        model = [active_purchases firstObject];
        [model updateWithProduct:product];
    }
    return model;
}

/**
 * Добавляем в покупку товарную единицу и обновляем значение текущего магазина
 */
- (void)updateWithProduct:(ProductModel *)product
{
    [self addProductsObject:product];
    [self setStore:[StoreModel findByPK:[CurrentUser lastUsedStoreID]]];
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
}

/**
 * Деактивация всех активных покупок
 */
- (void)deactivatePurchases
{
    NSArray *active_purchases = [PurchaseModel MR_findByAttribute:@"isActive" withValue:@YES];
    for (PurchaseModel *ap in active_purchases) {
        [ap setIsActive:@NO];
    }
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
}

@end

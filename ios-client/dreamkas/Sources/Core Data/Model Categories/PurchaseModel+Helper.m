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
        model = [PurchaseModel createByPk:[PurchaseItemModel pkForNewEntity]];
        [model setIsActive:@YES];
        [model updateWithProduct:product];
    }
    else if ([active_purchases count] < 1) {
        DPLogFast(@"[active_purchases count] < 1");
        // активных покупок нет - создаём новую
        model = [PurchaseModel createByPk:[PurchaseItemModel pkForNewEntity]];
        [model setIsActive:@YES];
        [model updateWithProduct:product];
    }
    else {
        DPLogFast(@"[active_purchases count] = 1");
        // активная покупка уже существует
        model = [active_purchases firstObject];
        [model updateWithProduct:product];
    }
    
    DPLogFast(@"purchase pk = %@", [model pk]);
    return model;
}

/**
 * Добавляем в покупку товарную единицу и обновляем значение текущего магазина
 */
- (void)updateWithProduct:(ProductModel *)product
{
    NSPredicate *predicate = [NSPredicate predicateWithFormat:@"productId = %@ AND self IN %@", [product pk], self.items];
    PurchaseItemModel *item = [PurchaseItemModel MR_findFirstWithPredicate:predicate];
    if (item == nil) {
        item = [PurchaseItemModel createByPk:[PurchaseItemModel pkForNewEntity]];
        [item setCount:@(1)];
        [item setProductId:[product pk]];
        // TODO: доработать логику работы с величиной измерения
        [item setMeasurement:@"шт."];
        [self addItemsObject:item];
    }
    else {
        [item setCount:@([[item count] integerValue]+1)];
    }
    
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

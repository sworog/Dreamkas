//
//  PurchaseModel.h
//  dreamkas
//
//  Created by sig on 17.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class PurchaseItemModel, StoreModel;

@interface PurchaseModel : AbstractModel

@property (nonatomic, retain) NSNumber * isActive;
@property (nonatomic, retain) NSNumber * itemsCount;
@property (nonatomic, retain) NSString * paymentType;
@property (nonatomic, retain) NSNumber * sumTotal;
@property (nonatomic, retain) StoreModel *store;
@property (nonatomic, retain) NSSet *items;
@end

@interface PurchaseModel (CoreDataGeneratedAccessors)

- (void)addItemsObject:(PurchaseItemModel *)value;
- (void)removeItemsObject:(PurchaseItemModel *)value;
- (void)addItems:(NSSet *)values;
- (void)removeItems:(NSSet *)values;

@end

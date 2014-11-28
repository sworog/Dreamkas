//
//  SaleModel.h
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class SaleItemModel, StoreModel;

@interface SaleModel : AbstractModel

@property (nonatomic, retain) NSString * paymentType;
@property (nonatomic, retain) NSNumber * sumTotal;
@property (nonatomic, retain) NSNumber * itemsCount;
@property (nonatomic, retain) NSSet *items;
@property (nonatomic, retain) StoreModel *store;
@end

@interface SaleModel (CoreDataGeneratedAccessors)

- (void)addItemsObject:(SaleItemModel *)value;
- (void)removeItemsObject:(SaleItemModel *)value;
- (void)addItems:(NSSet *)values;
- (void)removeItems:(NSSet *)values;

@end

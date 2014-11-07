//
//  CartModel.h
//  dreamkas
//
//  Created by sig on 07.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class ProductModel, StoreModel;

@interface CartModel : AbstractModel

@property (nonatomic, retain) NSNumber * sumTotal;
@property (nonatomic, retain) NSNumber * itemsCount;
@property (nonatomic, retain) NSString * paymentType;
@property (nonatomic, retain) StoreModel *store;
@property (nonatomic, retain) NSSet *products;
@end

@interface CartModel (CoreDataGeneratedAccessors)

- (void)addProductsObject:(ProductModel *)value;
- (void)removeProductsObject:(ProductModel *)value;
- (void)addProducts:(NSSet *)values;
- (void)removeProducts:(NSSet *)values;

@end

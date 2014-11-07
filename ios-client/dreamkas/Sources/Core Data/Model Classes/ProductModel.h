//
//  ProductModel.h
//  dreamkas
//
//  Created by sig on 07.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class CartModel, GroupModel;

@interface ProductModel : AbstractModel

@property (nonatomic, retain) NSString * barcode;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSNumber * purchasePrice;
@property (nonatomic, retain) NSNumber * sellingPrice;
@property (nonatomic, retain) NSString * sku;
@property (nonatomic, retain) GroupModel *group;
@property (nonatomic, retain) NSSet *carts;
@end

@interface ProductModel (CoreDataGeneratedAccessors)

- (void)addCartsObject:(CartModel *)value;
- (void)removeCartsObject:(CartModel *)value;
- (void)addCarts:(NSSet *)values;
- (void)removeCarts:(NSSet *)values;

@end

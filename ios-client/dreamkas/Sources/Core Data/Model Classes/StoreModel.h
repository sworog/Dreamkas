//
//  StoreModel.h
//  dreamkas
//
//  Created by sig on 07.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class CartModel;

@interface StoreModel : AbstractModel

@property (nonatomic, retain) NSString * address;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSSet *carts;
@end

@interface StoreModel (CoreDataGeneratedAccessors)

- (void)addCartsObject:(CartModel *)value;
- (void)removeCartsObject:(CartModel *)value;
- (void)addCarts:(NSSet *)values;
- (void)removeCarts:(NSSet *)values;

@end

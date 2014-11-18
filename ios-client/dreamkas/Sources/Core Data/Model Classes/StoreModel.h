//
//  StoreModel.h
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class SaleModel;

@interface StoreModel : AbstractModel

@property (nonatomic, retain) NSString * address;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSSet *purchases;
@end

@interface StoreModel (CoreDataGeneratedAccessors)

- (void)addPurchasesObject:(SaleModel *)value;
- (void)removePurchasesObject:(SaleModel *)value;
- (void)addPurchases:(NSSet *)values;
- (void)removePurchases:(NSSet *)values;

@end

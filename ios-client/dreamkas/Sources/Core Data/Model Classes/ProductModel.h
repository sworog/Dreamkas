//
//  ProductModel.h
//  dreamkas
//
//  Created by sig on 13.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class GroupModel, PurchaseModel;

@interface ProductModel : AbstractModel

@property (nonatomic, retain) NSString * barcode;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSNumber * purchasePrice;
@property (nonatomic, retain) NSNumber * sellingPrice;
@property (nonatomic, retain) NSString * sku;
@property (nonatomic, retain) NSSet *purchases;
@property (nonatomic, retain) GroupModel *group;
@end

@interface ProductModel (CoreDataGeneratedAccessors)

- (void)addPurchasesObject:(PurchaseModel *)value;
- (void)removePurchasesObject:(PurchaseModel *)value;
- (void)addPurchases:(NSSet *)values;
- (void)removePurchases:(NSSet *)values;

@end

//
//  StoreModel.h
//  dreamkas
//
//  Created by sig on 11.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class SaleModel;

@interface StoreModel : AbstractModel

@property (nonatomic, retain) NSString * address;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSSet *sales;
@end

@interface StoreModel (CoreDataGeneratedAccessors)

- (void)addSalesObject:(SaleModel *)value;
- (void)removeSalesObject:(SaleModel *)value;
- (void)addSales:(NSSet *)values;
- (void)removeSales:(NSSet *)values;

@end

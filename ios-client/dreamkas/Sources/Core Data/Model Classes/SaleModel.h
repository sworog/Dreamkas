//
//  SaleModel.h
//  dreamkas
//
//  Created by sig on 11.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class StoreModel;

@interface SaleModel : AbstractModel

@property (nonatomic, retain) NSNumber * itemsCount;
@property (nonatomic, retain) NSNumber * paymentAmountTendered;
@property (nonatomic, retain) NSNumber * paymentChange;
@property (nonatomic, retain) NSString * paymentType;
@property (nonatomic, retain) NSDate * saleDate;
@property (nonatomic, retain) NSNumber * sumTotal;
@property (nonatomic, retain) NSString * type;
@property (nonatomic, retain) StoreModel *store;

@end

//
//  SaleItemModel.h
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class SaleModel;

@interface SaleItemModel : AbstractModel

@property (nonatomic, retain) NSNumber * quantity;
@property (nonatomic, retain) NSString * productId;
@property (nonatomic, retain) SaleModel *sale;

@end

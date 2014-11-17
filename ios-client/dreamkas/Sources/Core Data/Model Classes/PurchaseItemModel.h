//
//  PurchaseItemModel.h
//  dreamkas
//
//  Created by sig on 17.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class PurchaseModel;

@interface PurchaseItemModel : AbstractModel

@property (nonatomic, retain) NSNumber * count;
@property (nonatomic, retain) NSString * productId;
@property (nonatomic, retain) PurchaseModel *purchase;

@end

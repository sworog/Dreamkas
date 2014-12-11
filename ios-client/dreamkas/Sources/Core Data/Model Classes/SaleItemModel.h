//
//  SaleItemModel.h
//  dreamkas
//
//  Created by sig on 11.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"


@interface SaleItemModel : AbstractModel

@property (nonatomic, retain) NSString * productId;
@property (nonatomic, retain) NSNumber * quantity;
@property (nonatomic, retain) NSDate * submitDate;

@end

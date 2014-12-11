//
//  ProductModel.h
//  dreamkas
//
//  Created by sig on 11.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class GroupModel;

@interface ProductModel : AbstractModel

@property (nonatomic, retain) NSString * barcode;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSNumber * purchasePrice;
@property (nonatomic, retain) NSNumber * sellingPrice;
@property (nonatomic, retain) NSString * sku;
@property (nonatomic, retain) NSString * units;
@property (nonatomic, retain) GroupModel *group;

@end

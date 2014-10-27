//
//  ProductModel.h
//  dreamkas
//
//  Created by sig on 27.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"


@interface ProductModel : AbstractModel

@property (nonatomic, retain) NSString * name;

@end

//
//  StoreModel.h
//  dreamkas
//
//  Created by sig on 15.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"


@interface StoreModel : AbstractModel

@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSString * address;

@end

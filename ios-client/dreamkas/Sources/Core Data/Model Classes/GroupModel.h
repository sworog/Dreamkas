//
//  GroupModel.h
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "AbstractModel.h"

@class ProductModel;

@interface GroupModel : AbstractModel

@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSSet *products;
@end

@interface GroupModel (CoreDataGeneratedAccessors)

- (void)addProductsObject:(ProductModel *)value;
- (void)removeProductsObject:(ProductModel *)value;
- (void)addProducts:(NSSet *)values;
- (void)removeProducts:(NSSet *)values;

@end

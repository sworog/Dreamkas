//
//  AbstractModel+Mapper.h
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractModel.h"

@interface AbstractModel (Mapper)

/**
 * Маппинг массива данных
 */
+ (NSArray*)mapModelsFromList:(NSArray*)list;

/**
 * Маппинг словаря данных
 */
+ (id)mapModelFromData:(NSDictionary*)data;

/**
 * Непосредственно маппинг полей
 */
- (id)mapModelFields:(NSDictionary*)data;

@end

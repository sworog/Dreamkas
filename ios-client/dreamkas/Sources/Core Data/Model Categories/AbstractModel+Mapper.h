//
//  AbstractModel+Mapper.h
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractModel.h"

@protocol ThoroughMappingProtocol <NSObject>
@optional
- (void)thoroughMap:(NSDictionary*)data forModelField:(NSString*)field;
@end


@interface AbstractModel (Mapper) <ThoroughMappingProtocol>

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

/**
 * Поиск модели по первичному ключу
 */
+ (id)findByPK:(NSString*)pk;

/** 
 * @name Методы создания моделей 
 */
+ (instancetype)createByPk:(NSString*)pk;
+ (NSString*)pkForNewEntity;

@end

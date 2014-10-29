//
//  AbstractModel+Mapper.m
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractModel+Mapper.h"

#define LOG_ON 0
#define PK_FIELD_KEYWORD @"id"

@implementation AbstractModel (Mapper)

#pragma mark - Методы маппинга моделей

/**
 * Маппинг массива данных
 */
+ (NSArray*)mapModelsFromList:(NSArray*)list
{
    if ([list isKindOfClass:[NSArray class]] == NO) {
        return nil;
    }
    
    NSMutableArray *result = [NSMutableArray new];
    
    for (NSDictionary *data in list) {
        AbstractModel *model = [self mapModelFromData:data];
        if (model)
            [result addObject:model];
    }
    
    return result;
}

/**
 * Маппинг словаря данных
 */
+ (id)mapModelFromData:(NSDictionary*)data
{
    @synchronized(data) {
        if ([data isKindOfClass:[NSDictionary class]] == NO) {
            return nil;
        }
        
        NSString *pk = [data valueForKey:PK_FIELD_KEYWORD];
        id model = [self findOrCreateByPk:pk];
        return [model mapModelFields:data];
    }
}

/**
 * Непосредственно маппинг полей
 */
- (id)mapModelFields:(NSDictionary*)data
{
    // установка первичного ключа
    NSString *pk_value = [data valueForKey:PK_FIELD_KEYWORD];
    if ([pk_value length] < 1) {
        pk_value = [self.class pkForNewEntity];
    }
    self.pk = pk_value;
    
    // установка базовых полей, чьи имена идентичны именам таковых в ответе сервера
    for (NSString *key in [data allKeys]) {
        if (([self respondsToSelector:NSSelectorFromString(key)] == NO) &&
            (([data[key] isKindOfClass:[NSDictionary class]]) || ([data[key] isKindOfClass:[NSArray class]])) == NO) {
            DPLog(LOG_ON, @"There is no @property %@", key);
            continue;
        }
        
        if (([data[key] isKindOfClass:[NSDictionary class]]) || ([data[key] isKindOfClass:[NSArray class]])) {
            DPLog(LOG_ON, @"Mapping value Class is %@", NSStringFromClass([data[key] class]));
            
            if ([self respondsToSelector:@selector(thoroughMap:forModelField:)]) {
                [self thoroughMap:data forModelField:key];
            }
            continue;
        }
        
        // установка значения поля ответа в свойство модели
        [self setValue:data[key] forKeyPath:key];
    }
    
    return self;
}

#pragma mark - Методы поиска моделей

+ (id)findOrCreateByPk:(NSString*)pk
{
    NSManagedObject *model = [self findByPK:pk];
    
    if (model == nil) {
        model = [self createByPk:pk];
    }
    
    return model;
}

/**
 * Поиск модели по первичному ключу
 */
+ (id)findByPK:(NSString*)pk
{
    return [self MR_findFirstByAttribute:@"pk"
                               withValue:pk
                               inContext:[NSManagedObjectContext MR_defaultContext]];
}

#pragma mark - Методы создания моделей

+ (instancetype)createByPk:(NSString*)pk
{
    AbstractModel *model = [self MR_createEntityInContext:[NSManagedObjectContext MR_defaultContext]];
    model.pk = pk;
    
    return model;
}

+ (NSString*)pkForNewEntity
{
    DPLog(LOG_ON, @"");
    
    // находим модель с максимальным значением PK
    AbstractModel *model = [self MR_findFirstOrderedByAttribute:@"pk"
                                                      ascending:NO
                                                      inContext:[NSManagedObjectContext MR_defaultContext]];
    NSNumber *new_pk = @(1);
    if (model) {
        new_pk = @(model.pk.intValue + 1);
    }
    
    while ([AbstractModel findByPK:[new_pk stringValue]]) {
        new_pk = @(new_pk.intValue + 1);
    }
    return [new_pk stringValue];
}

@end

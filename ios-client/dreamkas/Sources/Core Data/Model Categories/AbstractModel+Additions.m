//
//  AbstractModel+Additions.m
//  dreamkas
//
//  Created by sig on 15.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractModel+Additions.h"

@implementation AbstractModel (Additions)

+ (id)findByPK:(NSString*)pk
{
    return [self MR_findFirstByAttribute:@"pk"
                               withValue:pk
                               inContext:[NSManagedObjectContext MR_defaultContext]];
}

+ (id)findOrCreateByPk:(NSString*)pk
{
    NSManagedObject *model = [self findByPK:pk];
    if (model == nil) {
        model = [self createByPk:pk];
    }
    
    return model;
}

+ (instancetype)createByPk:(NSString*)pk
{
    AbstractModel *model = [self MR_createEntityInContext:[NSManagedObjectContext MR_defaultContext]];
    model.pk = pk;
    
    return model;
}

- (id)mapWithRESTData:(NSDictionary*)data
{
    // маппинг базовых полей
    self.pk = data[@"id"];
    
    for (NSString *key in [data allKeys]) {
        if ([self respondsToSelector:@selector(key)] == NO) {
            continue;
        }
        
        // если поле - не словарь
        if ([[data[key] class] isSubclassOfClass:[NSDictionary class]] == NO)
            [self setValue:data[key] forKeyPath:key];
    }
    
//    if (self.pk == nil)
//        self.pk = [self.class pkForNewEntity];
    
    return self;
}

+ (id)modelWithRESTData:(NSDictionary*)data
{
    @synchronized(data) {
        NSString *pk = data[@"id"];
        id model = [self findOrCreateByPk:pk];
        return [model mapWithRESTData:data];
    }
}

+ (NSArray*)modelsListWithRESTData:(NSArray*)data
{
    NSMutableArray *result = [NSMutableArray new];
    
    for (NSDictionary *elem_data in data) {
        [result addObject:[self modelWithRESTData:elem_data]];
    }
    
    return result;
}

@end

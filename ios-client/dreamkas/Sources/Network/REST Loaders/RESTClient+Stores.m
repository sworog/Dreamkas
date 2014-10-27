//
//  RESTClient+Stores.m
//  dreamkas
//
//  Created by sig on 15.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient+Stores.h"
#import "StoreModel.h"

#define LOG_ON 1

@implementation RESTClient (Stores)

/**
 * Получение списка магазинов
 */
- (void)requestStores:(ArrayResponseBlock)completionBlock
{
    [self GET:CompleteURL(@"stores.json")
   parameters:nil
      success:^(NSURLSessionDataTask * __unused task, id JSON) {
          DPLog(LOG_ON, @"Получили распарсенный ответ сервера");
          
          // маппинг полученных данных в экземпляры сущностей
          NSArray *models = [StoreModel mapModelsFromList:JSON];
          
          DPLog(LOG_ON, @"Закончили маппинг ответа сервера");
          
          // сохраняем экземпляры сущностей в БД
          [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
          
          DPLog(LOG_ON, @"Сохранили изменения в БД");
          
          completionBlock(models, nil);
      } failure:^(NSURLSessionDataTask *__unused task, NSError *error) {
          // передаем данные в блок обработки
          if (completionBlock)
              completionBlock(nil, error);
      }];
}

/**
 * Получение продуктов по названию, SKU или штрих-коду
 */
- (void)requestProductsByQuery:(NSString *)queryValue
                  onCompletion:(ArrayResponseBlock)completionBlock
{
    if (queryValue.length < 1) {
        if (completionBlock)
            completionBlock(nil, [NSError errorWithDomain:@"Wrong value for <queryValue>."
                                                     code:-10 userInfo:nil]);
        return;
    }
    
    DPLog(LOG_ON, @"");
    NSDictionary *params = @{@"query": queryValue,
                             @"properties": @[@"name", @"sku", @"barcode"]};
    
    [self GET:CompleteURL(@"products/search.json")
   parameters:params
      success:^(NSURLSessionDataTask * __unused task, id JSON) {
          DPLog(LOG_ON, @"Получили распарсенный ответ сервера");
          
          // маппинг полученных данных в экземпляры сущностей
          NSArray *models = [ProductModel mapModelsFromList:JSON];
          
          DPLog(LOG_ON, @"Закончили маппинг ответа сервера");
          
          // сохраняем экземпляры сущностей в БД
          [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
          
          DPLog(LOG_ON, @"Сохранили изменения в БД");
          
          completionBlock(models, nil);
      } failure:^(NSURLSessionDataTask *__unused task, NSError *error) {
          // передаем данные в блок обработки
          if (completionBlock)
              completionBlock(nil, error);
      }];
}

@end

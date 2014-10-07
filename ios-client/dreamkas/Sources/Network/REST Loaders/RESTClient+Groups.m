//
//  RESTClient+Groups.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient+Groups.h"

#define LOG_ON 1

@implementation RESTClient (Groups)

/**
 * Получение списка групп
 */
- (void)requestGroups:(ArrayResponseBlock)completionBlock
{
    [self GET:CompleteURL(@"catalog/groups.json")
   parameters:nil
      success:^(NSURLSessionDataTask * __unused task, id JSON) {
          DPLog(LOG_ON, @"Получили распарсенный ответ сервера");
          
          // маппинг полученных данных в экземпляры сущностей
          NSArray *models = nil; //[GroupModel modelsListWithRESTData:data];
          
          DPLog(LOG_ON, @"Закончили маппинг ответа сервера");
          
          // сохраняем экземпляры сущностей в БД
          //[[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
          
          DPLog(LOG_ON, @"Сохранили изменения в БД");
          
          completionBlock(models, nil);
    } failure:^(NSURLSessionDataTask *__unused task, NSError *error) {
        // передаем данные в блок обработки
        if (completionBlock)
            completionBlock(nil, error);
    }];
}

@end

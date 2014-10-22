//
//  DataRequestingDelegate.h
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@protocol DataRequestingDelegate <NSObject>

/** @name Параметры для организации механизма лимитированной загрузки */
@property (nonatomic, readonly) NSInteger queryLimit;
@property (nonatomic, readonly) NSInteger queryOffset;
@property (nonatomic, readonly) BOOL requestIsStarted;
@property (nonatomic, readonly) BOOL isRefreshingRequest;

/** Метод создания словаря параметров НЕ лимитированного запроса */
- (NSDictionary*)createQueryParams;

/** Метод создания словаря параметров лимитированного запроса */
- (NSDictionary*)createLimitedQueryParams;

/** Стандартный размер лимитированного запроса */
- (NSInteger)defaultQueryLimit;

/** Сброс параметров лимитирования */
- (void)resetQueryParams;

/** Метод, инициирующий загрузку данных с сервера */
- (void)requestDataFromServer;

/** Метод, вызываемый по завершении обновления записей в локальной БД */
- (void)onMappingCompletion:(NSArray*)records;

/** Метод, вызываемый в случае ошибки при обновлении записей в локальной БД */
- (void)onMappingFailure:(NSError*)error;

@end

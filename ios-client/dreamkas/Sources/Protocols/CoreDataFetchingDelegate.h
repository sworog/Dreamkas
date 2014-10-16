//
//  CoreDataFetchingDelegate.h
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@protocol CoreDataFetchingDelegate <NSObject>

/** Метод возвращает название класса, чьи экземпляры выбираются из БД и выводятся в таблице */
- (Class)fetchClass;

/** Поле сортировки в выдаче результатов выборки */
- (NSString*)fetchSortedField;

/** Направление сортировки в выдаче результатов выборки */
- (BOOL)isFetchAscending;

/** Метод для выполнения выборки с текущими настройками fetch-контроллера */
- (void)performFetchRequest;

/** Условие выборки */
- (NSPredicate*)fetchPredicate;

/** Размер порции загружаемых единовременно данных */
- (NSInteger)fetchBatchSize;

/** Обработчик события выполнения запроса (в качестве параметра получает число выбранных записей) */
- (void)onFetchCompletion:(NSInteger)itemsCount;

/** Метод, уведомляющий об окончании работы fetch-контроллера после изменений полей моделей */
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller;

@end

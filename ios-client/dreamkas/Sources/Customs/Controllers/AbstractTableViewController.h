//
//  AbstractTableViewController.h
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"
#import "CoreDataFetchingDelegate.h"
#import "DataRequestingDelegate.h"

/**
 Модифицированный контроллер со встроенной таблицей, тесно интегрированный
 с fetch-контроллером, и поддерживающий режим лимитированной загрузки-отображения данных.
 */

@interface AbstractTableViewController : AbstractViewController <UITableViewDelegate, UITableViewDataSource, CoreDataFetchingDelegate, DataRequestingDelegate>

/** Cтандартный контроллер для управления процессом и результатами выборки из Core Data */
@property (nonatomic, readonly) NSFetchedResultsController *fetchedResultsController;

/** Таблица, в которой отображаются данные */
@property (nonatomic, readwrite) IBOutlet UITableView *tableViewItem;

/** Индикатор перезагрузки данных на странице. Реализует индикацию вида Pull to refresh */
@property (nonatomic, readonly) UIRefreshControl *headerRefreshControl;

/** Флаг, уведомляющий об использовании элемента PullDown для обновления таблицы */
@property (nonatomic) BOOL pullDownActionEnabled;

/** Флаг, уведомляющий об использовании механизма лимитированных загрузок */
@property (nonatomic) BOOL limitedQueryEnabled;

/** Настройка очередной ячейки таблицы с помощью полей соответствующего объекта */
- (void)configureCell:(UITableViewCell<CustomDataCellDelegate>*)cell
          atIndexPath:(NSIndexPath*)indexPath
           withObject:(NSManagedObject*)object;

/** Kласс ячейки таблицы (по умолчанию UITableViewCell) */
- (Class)cellClass;

/** Kоличество элементов в заданной секции */
- (NSInteger)countOfItemsForSection:(NSInteger)section;

/** Oбщее количество элементов в таблице (для таблиц с единственной секцией)*/
- (NSInteger)countOfItems;

/** Метод возвращает набор параметров, которые используются при кэшировании */
- (NSMutableDictionary*)dictionaryForUpdates;

/** Метод, инициирующий перерисовку таблицы.
 (например, элементами с другой выборкой - фильтр) */
- (void)shouldReloadTableView;

/** Метод, уведомляющий об изменении объекта fetch-контроллером в связи с изменениями полей объектов БД */
- (void)controller:(NSFetchedResultsController *)controller
   didChangeObject:(id)anObject
       atIndexPath:(NSIndexPath *)indexPath
     forChangeType:(NSFetchedResultsChangeType)theType
      newIndexPath:(NSIndexPath *)newIndexPath;

@end

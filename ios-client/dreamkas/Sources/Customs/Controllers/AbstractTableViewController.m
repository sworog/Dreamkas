//
//  AbstractTableViewController.m
//  dreamkas
//
//  Created by sig on 16.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractTableViewController.h"

#define LOG_ON 1
#define DefaultFetchingField @"pk"

@interface AbstractTableViewController () <NSFetchedResultsControllerDelegate>

@end

@implementation AbstractTableViewController

@synthesize fetchedResultsController, queryLimit, queryOffset, requestIsStarted, isRefreshingRequest, headerRefreshControl;

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // настраиваем таблицу
    self.tableViewItem.dataSource = self;
    self.tableViewItem.delegate = self;
    self.tableViewItem.backgroundColor = [UIColor clearColor];
    self.tableViewItem.autoresizingMask = UIViewAutoresizingFlexibleHeight;
    self.tableViewItem.separatorStyle = UITableViewCellSeparatorStyleNone;
    
    // если необходимо, то включаем поддержку Pull2Refresh механизма для обновления страницы
    if (self.pullDownActionEnabled) {
        UITableViewController *table_view_controller = [UITableViewController new];
        table_view_controller.tableView = self.tableViewItem;
        
        headerRefreshControl = [UIRefreshControl new];
        [headerRefreshControl setTintColor:DefaultBlackColor];
        [headerRefreshControl addTarget:self action:@selector(startNonLimitedRequest)
                       forControlEvents:UIControlEventValueChanged];
        table_view_controller.refreshControl = headerRefreshControl;
    }
    
    // сброс параметров лимитирования
    [self resetQueryParams];
}

- (void)didMoveToParentViewController:(UIViewController *)parent
{
    [super didMoveToParentViewController:parent];
    
    // если необходимо, то включаем поддержку Pull2Refresh механизма для лимитированной подгрузки
    if (self.limitedQueryEnabled) {
        __weak typeof(self)weak_self = self;
        
        [self.tableViewItem addPullToRefreshWithActionHandler:^{
            __strong typeof(self)strong_self = weak_self;
            [strong_self startLimitedRequest];
        } position:SVPullToRefreshPositionBottom];
        
        BOOL show_p2r = ([self countOfItems] >= queryLimit);
        [self.tableViewItem setShowsPullToRefresh:show_p2r];
    }
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    [self.tableViewItem selectRowAtIndexPath:nil animated:NO scrollPosition:UITableViewScrollPositionNone];
    
    // делаем проверку на необходимость обновления страницы данными с сервера
    [self checkoutLocalData];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    fetchedResultsController = nil;
}

#pragma mark - Методы NSFetchedResultsController

/**
 *  Метод для формирования fetchedResultsController
 */
- (NSFetchedResultsController *)fetchedResultsController
{
    DPLog(LOG_ON, @"");
    
    if (fetchedResultsController != nil)
        return fetchedResultsController;
    
    DPLog(LOG_ON, @"Creating new fetchedResultsController");
    
    Class class = [self fetchClass];
    fetchedResultsController = [class MR_fetchAllSortedBy:[self fetchSortedField]
                                                ascending:[self isFetchAscending]
                                            withPredicate:[self fetchPredicate]
                                                  groupBy:[self fetchGroupBy]
                                                 delegate:self
                                                inContext:[NSManagedObjectContext MR_defaultContext]];
    
    // устанавливаем размер порций загружаемых данных
    fetchedResultsController.fetchRequest.fetchBatchSize = [self fetchBatchSize];
    
    [self performFetchRequest];
    
    [self onFetchCompletion:fetchedResultsController.fetchedObjects.count];
    
    return fetchedResultsController;
}

/**
 *  Метод для выполнения выборки с текущими настройками NSFetchedResultsController'a
 */
- (void)performFetchRequest
{
    NSError *error = nil;
    if ([fetchedResultsController performFetch:&error] == NO) {
        // обработка ошибок
        // ..
        
        DPLog(LOG_ON, @"fetch error: %@", error);
    }
}

/**
 *  Метод, уведомляющий о начале работы fetch-контроллера
 *  в связи с изменениями полей объектов БД
 */
- (void)controllerWillChangeContent:(NSFetchedResultsController *)controller
{
    DPLog(LOG_ON, @"controllerWillChangeContent begin");
    
    // разрешаем асинхронное обновление ячеек таблицы
    [self.tableViewItem beginUpdates];
    
    DPLog(LOG_ON, @"controllerWillChangeContent end");
}

/**
 *  Метод, уведомляющий об окончании работы fetch-контроллера
 *  в связи с изменениями полей объектов БД
 */
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    DPLog(LOG_ON, @"controllerDidChangeContent begin");
    
    // запрещаем асинхронное обновление ячеек таблицы
    [self.tableViewItem endUpdates];
    
    DPLog(LOG_ON, @"controllerDidChangeContent end");
}

/**
 *  Метод, уведомляющий об изменении объекта fetch-контроллером
 *  в связи с изменениями полей объектов БД
 */
- (void)controller:(NSFetchedResultsController *)controller
   didChangeObject:(id)anObject
       atIndexPath:(NSIndexPath *)indexPath
     forChangeType:(NSFetchedResultsChangeType)theType
      newIndexPath:(NSIndexPath *)newIndexPath
{
    DPLog(LOG_ON, @"didChangeObject begin");
    
    switch (theType) {
        case NSFetchedResultsChangeInsert:
            DPLog(LOG_ON, @"NSFetchedResultsChangeInsert");
            [self.tableViewItem insertRowsAtIndexPaths:@[newIndexPath] withRowAnimation:UITableViewRowAnimationNone];
            break;
            
        case NSFetchedResultsChangeUpdate:
            DPLog(LOG_ON, @"NSFetchedResultsChangeUpdate");
            [self.tableViewItem reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationNone];
            break;
            
        case NSFetchedResultsChangeDelete:
            DPLog(LOG_ON, @"NSFetchedResultsChangeDelete");
            [self.tableViewItem deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationNone];
            break;
            
        case NSFetchedResultsChangeMove:
            DPLog(LOG_ON, @"NSFetchedResultsChangeMove");
            [self.tableViewItem moveRowAtIndexPath:indexPath toIndexPath:newIndexPath];
            break;
            
        default:
            break;
    }
    
    DPLog(LOG_ON, @"didChangeObject end");
}

/**
 *  Метод, уведомляющий о добавлении или удалении секции
 */
- (void)controller:(NSFetchedResultsController *)controller
  didChangeSection:(id <NSFetchedResultsSectionInfo>)sectionInfo
           atIndex:(NSUInteger)sectionIndex forChangeType:(NSFetchedResultsChangeType)type
{
    DPLog(LOG_ON, @"didChangeSection begin");
    
    switch(type) {
        case NSFetchedResultsChangeInsert:
            [self.tableViewItem insertSections:[NSIndexSet indexSetWithIndex:sectionIndex]
                              withRowAnimation:UITableViewRowAnimationNone];
            break;
        case NSFetchedResultsChangeUpdate:
            [self.tableViewItem reloadSections:[[NSIndexSet alloc] initWithIndex:sectionIndex]
                              withRowAnimation:UITableViewRowAnimationNone];
            break;
        case NSFetchedResultsChangeDelete:
            [self.tableViewItem deleteSections:[NSIndexSet indexSetWithIndex:sectionIndex]
                              withRowAnimation:UITableViewRowAnimationNone];
            break;
        default:
            break;
    }
    
    DPLog(LOG_ON, @"didChangeSection end");
}

#pragma mark - Вспомогательные методы NSFetchedResultsController'a

/**
 * Метод возвращает название класса, чьи экземпляры выбираются из БД и выводятся в таблице
 */
- (Class)fetchClass
{
    NSAssert(0, @"Требуется переопределить метод fetchClass");
    return nil;
}

/**
 * Метод возвращает название параметра, по которому происходит сортировка при выборке из БД
 */
- (NSString*)fetchSortedField
{
    return DefaultFetchingField;
}

/**
 *  Метод показывает направление сортировки при выборке
 *  (YES - по возрастанию, NO - по убыванию)
 */
- (BOOL)isFetchAscending
{
    return NO;
}

/**
 *  Метод возвращает предикат, по которому происходит фильтрация при выборке из БД
 */
- (NSPredicate*)fetchPredicate
{
    // nil - берет все экземпляры сущностей БД (указанного в fetchClass класса)
    return nil;
    
    // redefine me if needed
}

/**
 *  Метод возвращает имя поля, по которому происходит группировка при выборке из БД
 */
- (NSString*)fetchGroupBy
{
    // nil - без группировки
    return nil;
}

/**
 *  Метод возвращает число записей, которые будут выбираться одновременно при запросе к локальной БД.
 */
- (NSInteger)fetchBatchSize
{
    return 1000;
}

/**
 *  Обработчик события выполнения запроса (в качестве параметра получает число выбранных записей)
 */
- (void)onFetchCompletion:(NSInteger)itemsCount
{
    DPLog(LOG_ON, @"count = %d", itemsCount);
    
    // остановка анимаций загрузки
    [self stopLoadingAnimations];
    
    // override me
}

/**
 *  Метод, инициирующий перерисовку таблицы (например, элементами с другой выборкой - фильтр)
 */
- (void)shouldReloadTableView
{
    DPLog(LOG_ON, @"");
    
    // обновляем данные в таблице
    fetchedResultsController = nil;
    [self.tableViewItem reloadData];
    
    // таблица могла перерисоваться и с отсутствующими данными
    // - поэтому запускаем проверку необходимости загрузки выпусков
    [self checkoutLocalData];
}

#pragma mark - Методы загрузки данных с сервера (по необходимости)

/**
 *  Метод, проверяющий необходимость обновления
 *  экземпляров сущности <AbstractModel> локальной БД
 */
- (void)checkoutLocalData
{
    DPLog(LOG_ON, @"");
    
    // в случае, если данные, содержащиеся в локальной БД,
    // устарели - делаем запрос к серверу и обновляем их
    if ([self needUpdateData]) {
        // сброс параметров лимитирования
        [self resetQueryParams];
        
        dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_LOW, (unsigned long)NULL), ^(void) {
            [self requestDataFromServer];
        });
    }
    
    // если данные не устарели, то таблица сама построится
    // по данным из локальной БД
}

/**
 *  Метод, инициирующий загрузку данных с сервера
 */
- (void)requestDataFromServer
{
    DPLog(LOG_ON, @"");
    
    if (requestIsStarted)
        return;
    
    // подготавливаемся к началу загрузки
    [self showLoading];
    requestIsStarted = YES;
    
    // __block onCompletion --> вызываем onMappingCompletion
    // __block onError      --> вызываем onMappingFailure
    
    // addition me
    
    // Example:
    /*
     __weak typeof(self)weak_self = self;
     [NetworkManager requestCategories:[self createLimitedQueryParams]
     onCompletion:^(NSArray *data, NSError *error)
     {
     __strong typeof(self)strong_self = weak_self;
     
     if (error != nil) {
     [strong_self onMappingFailure:error];
     return;
     }
     [strong_self onMappingCompletion:data];
     }];
     */
}

/**
 *  Метод, вызываемый по завершении обновления записей в локальной БД
 */
- (void)onMappingCompletion:(NSArray*)records
{
    DPLog(LOG_ON, @"");
    
    requestIsStarted = NO;
    
    // ставим метку с датой/временем обновления данных
    [self setTimestamp];
    
    // если включен механизм лимитирования, то указываем границы для следующей порции данных
    if (self.limitedQueryEnabled && records.count > 0) {
        // если получили результат обновления всей таблицы
        if (isRefreshingRequest) {
            isRefreshingRequest = NO;
            queryOffset = records.count;
        }
        else {
            // если получили результат лимитированной подгрузки
            queryOffset += MIN(queryLimit, records.count);
        }
    }
    
    // если записи в таблице отсутствуют (по результатам выборки ранее)
    // и, следовательно, fetchedResultsController не следит ни за одним объектом БД
    if ([self countOfItems] < 1 || records.count < 1) {
        fetchedResultsController = nil;
        [self.tableViewItem reloadData];
        
        // остановка анимаций загрузки
        // на onFetchCompletion
    }
    else {
        // таблица обновляется самостоятельно
        // в методе controller:didChangeObject:
        
        // остановка анимаций загрузки
        [self stopLoadingAnimations];
    }
}

/**
 *  Метод, вызываемый в случае ошибки при обновлении записей в локальной БД
 */
- (void)onMappingFailure:(NSError*)error
{
    DPLog(LOG_ON, @"error : %@", error);
    
    requestIsStarted = NO;
    
    // остановка анимаций загрузки
    [self stopLoadingAnimations];
    
    [DialogHelper showRequestError];
}

#pragma mark - Вспомогательные методы при загрузке данных

/**
 *  Метод для проверки необходимости обновления информации c сервера
 */
- (BOOL)needUpdateData
{
    return YES;
    
//    TODO: решить вопрос с проверкой актуальности локальных данных
//    return ([self countOfItemsForSection:0] == 0 ||
//            [[self fetchClass] needToUpdateFor:[self dictionaryForUpdates]]);
}

/**
 *  Установка метки с датой/временем последнего обновления данных с сервера
 */
- (void)setTimestamp
{
    DPLog(LOG_ON, @"");
    
//    TODO: решить вопрос с установкой временных засечек на обновление
//    [[self fetchClass] setLastUpdateTime:[NSDate date] forParams:[self dictionaryForUpdates]];
}

/**
 * Метод возвращает набор параметров, которые используются при кэшировании.
 * Это набор специфичных данному контроллеру параметров,
 * по которым можно определить, нужно ли обновлять данные в текущий момент времени.
 * Также по ним ставится временнАя засечка.
 */
- (NSMutableDictionary*)dictionaryForUpdates
{
    DPLog(LOG_ON, @"");
    return @{@"model" : NSStringFromClass([self fetchClass]),
             @"controller" : NSStringFromClass([self class])}.mutableCopy;
    
    // addition me if needed
}

/**
 *  Метод останавливает анимации загрузки
 *  и показывает/скрывает нижний pull2refresh
 */
- (void)stopLoadingAnimations
{
    if (requestIsStarted)
        return;
    
    // останавливаем индикацию процесса перегрузки таблицы
    [self hideLoading];
    
    // показываем или скрываем pull-up-2-refresh в зависимости от фактического размера таблицы
    if (self.limitedQueryEnabled) {
        [self.tableViewItem.pullToRefreshView stopAnimating];
        
        BOOL show_p2r = ([self countOfItems] >= queryLimit);
        [self.tableViewItem setShowsPullToRefresh:show_p2r];
    }
    
    // если включен механизм pull-down-2-refresh, то останавливаем индикацию
    if (self.pullDownActionEnabled) {
        [headerRefreshControl endRefreshing];
    }
}

#pragma mark - Методы для организации лимитированных загрузок и массовых обновлений

/**
 *  Запуск загрузки по механизму Pull-To-Refresh (нижняя индикация)
 */
- (void)startLimitedRequest
{
    // gtfo, если лимитированная подгрузка запрещена или предыдущий запрос не закончился
    if ((self.limitedQueryEnabled == NO) || requestIsStarted)
        return;
    
    DPLog(LOG_ON, @"Доскроллили до конца списка, загружаем новый блок");
    
    // показываем индикацию загрузки только в статус-баре
    [self showLoading];
    
    // если список заведомо не пуст, а параметр queryOffset сброшен
    if (([self countOfItems] > 0) && (queryOffset == 0))
        queryOffset = [self countOfItems];
    
    __weak typeof(self)weak_self = self;
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_LOW, (unsigned long)NULL), ^(void) {
        __strong typeof(self)strong_self = weak_self;
        [strong_self requestDataFromServer];
    });
}

/**
 *  Запуск массового обновления по механизму Pull-To-Refresh (верхняя индикация)
 */
- (void)startNonLimitedRequest
{
    // gtfo, если массовое обновление запрещено или предыдущий запрос не закончился
    if ((self.pullDownActionEnabled == NO) || requestIsStarted)
        return;
    
    DPLog(LOG_ON, @"Запущен механизм массового обновления содержимого списка");
    
    // показываем индикацию загрузки только в статус-баре
    [self showLoading];
    
    // сбрасываем offset, чтобы сформировать корректный словарь для запроса
    queryOffset = 0;
    
    __weak typeof(self)weak_self = self;
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_LOW, (unsigned long)NULL), ^(void) {
        __strong typeof(self)strong_self = weak_self;
        [strong_self requestDataFromServer];
    });
}

/**
 *  Метод создания словаря параметров НЕ лимитированного запроса
 */
- (NSDictionary*)createQueryParams
{
    return nil;
}

/**
 *  Метод создания словаря параметров лимитированного запроса
 */
- (NSDictionary*)createLimitedQueryParams
{
    NSMutableDictionary *params = [NSMutableDictionary new];
    NSInteger count_of_items = [self countOfItems];
    
    // если количество элементов в таблице меньше минимального - разрешаем загрузить больше
    if (count_of_items < [self defaultQueryLimit])
        count_of_items = [self defaultQueryLimit];
    
    // если запрос обновление списка (список заведомо не пуст)
    if (self.pullDownActionEnabled && (queryOffset == 0) && (count_of_items > 0)) {
        DPLog(LOG_ON, @"Запрос на обновление списка (меняем limit & offset)");
        params[@"limit"] = @(count_of_items);
        params[@"offset"] = @(0);
        isRefreshingRequest = YES;
    }
    else if (self.limitedQueryEnabled) {
        // если лимитирование разрешено, добавляем в запрос соответствующие параметры
        params[@"limit"] = @(queryLimit);
        params[@"offset"] = @(queryOffset);
    }
    
    NSDictionary *query_params = [self createQueryParams];
    if (query_params != nil)
        [params addEntriesFromDictionary:query_params];
    
    DPLog(LOG_ON, @"query params : %@", params);
    
    return params;
}

/**
 *  Стандартный размер лимитированного запроса
 */
- (NSInteger)defaultQueryLimit
{
    if ([self limitedQueryEnabled]) {
        NSAssert(0, @"Требуется переопределить метод defaultQueryLimit");
    }
    
    return 0;
}

/**
 *  Сброс параметров лимитирования
 */
- (void)resetQueryParams
{
    queryOffset = 0;
    
    // стандартный размер лимитированного запроса
    queryLimit = [self defaultQueryLimit];
}

#pragma mark -  Методы UITableView

/**
 *  Метод возвращает количество секций в таблице
 */
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return self.fetchedResultsController.sections.count;
}

/**
 *  Метод возвращает количество ячеек в таблице
 */
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [self countOfItemsForSection:section];
}

/**
 *  Метод возвращает ячейку таблицы по указанному адресу
 */
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cell_identifier = [NSString stringWithFormat:@"Cell_%@", [self fetchClass]];
    
    UITableViewCell<CustomDataCellDelegate> *cell = [tableView dequeueReusableCellWithIdentifier:cell_identifier];
    if (cell == nil) {
        cell = [[[self cellClass] alloc] initWithStyle:UITableViewCellStyleValue1
                                       reuseIdentifier:cell_identifier];
    }    
    [cell setAccessibilityLabel:AI_Common_CellAtIndexPath((long)[indexPath section], (long)[indexPath row])];
    DPLogFast(@"cell accessibilityLabel = %@", [cell accessibilityLabel]);
    
    id object = [self.fetchedResultsController objectAtIndexPath:indexPath];
    [self configureCell:cell atIndexPath:indexPath withObject:object];
    
    return cell;
}

/**
 *  Метод возвращает заголовок секции таблицы по указанному адресу
 */
- (NSString*)tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section
{
    id <NSFetchedResultsSectionInfo> sectionInfo = self.fetchedResultsController.sections[section];
    return [sectionInfo name];
}

#pragma mark - Вспомогательные методы UITableView

/**
 * Настройка очередной ячейки таблицы с помощью полей соответствующего объекта
 */
- (void)configureCell:(UITableViewCell<CustomDataCellDelegate>*)cell
          atIndexPath:(NSIndexPath*)indexPath
           withObject:(NSManagedObject*)object
{
    [cell configureWithModel:object];
    
    // redefine me if needed
    // (для случая с группами/секциями и сложным дизайном)
}

/**
 * Метод возвращает название класса ячейки, чьи экземпляры формируют таблицы
 */
- (Class)cellClass
{
    return [UITableViewCell class];
}

/**
 * Количество элементов в заданной секции
 */
- (NSInteger)countOfItemsForSection:(NSInteger)section
{
    if (self.fetchedResultsController.sections.count == 0)
        return 0;
    
    id <NSFetchedResultsSectionInfo> sectionInfo = self.fetchedResultsController.sections[section];
    return [sectionInfo numberOfObjects];
}

/**
 * Общее количество элементов в таблице
 * (метод-обертка над методом "countOfItemsForSection:" для таблиц с 1 секцией)
 */
- (NSInteger)countOfItems
{
    return [self countOfItemsForSection:0];
}

@end

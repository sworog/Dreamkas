//
//  ProductsViewController.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ProductsViewController.h"
#import "ProductCell.h"

@interface ProductsViewController ()

@end

@implementation ProductsViewController

#pragma mark - Инициализация

- (void)initialize
{
    // выключаем для контроллера массовое обновление и лимитированные запросы
    [self setPullDownActionEnabled:YES];
    [self setLimitedQueryEnabled:NO];
}

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    if (self.groupInstance)
        self.title = self.groupInstance.name;
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    // ..
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (void)searchButtonClicked
{
    DPLogFast(@"");
    
    // ..
}

#pragma mark - Методы CustomTableViewController

/**
 *  Kласс ячейки таблицы
 */
- (Class)cellClass
{
    return [ProductCell class];
}

/**
 *  Метод возвращает название класса, чьи экземпляры выбираются из БД и выводятся в таблице
 */
- (Class)fetchClass
{
    return [ProductModel class];
}

/**
 * Метод возвращает название параметра, по которому происходит сортировка при выборке из БД
 */
- (NSString*)fetchSortedField
{
    return @"name";
}

/**
 *  Метод показывает направление сортировки при выборке
 *  (YES - по возрастанию, NO - по убыванию)
 */
- (BOOL)isFetchAscending
{
    return YES;
}

/**
 *  Метод возвращает предикат, по которому происходит фильтрация при выборке из БД
 */
- (NSPredicate*)fetchPredicate
{
    NSMutableArray *argument_array = [NSMutableArray new];
    NSMutableArray *format_array = [NSMutableArray new];
    NSPredicate *predicate = nil;
    
    // фильтр по категории
    if (self.groupInstance) {
        [format_array addObject:@"self IN %@"];
        [argument_array addObject:[self.groupInstance products]];
    }
    
    // ..
    
    // формируем предикат по полученным данным
    predicate = [NSPredicate predicateWithFormat:[format_array componentsJoinedByString:@" AND "]
                                   argumentArray:argument_array];
    return predicate;
}

/**
 *  Метод, инициирующий загрузку данных с сервера
 */
- (void)requestDataFromServer
{
    [super requestDataFromServer];
    
    __weak typeof(self)weak_self = self;
    [NetworkManager requestProductsFromGroup:self.groupInstance.pk
                                onCompletion:^(NSArray *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        
        if (error != nil) {
            [strong_self onMappingFailure:error];
            return;
        }
        [strong_self onMappingCompletion:data];
    }];
}

/**
 *  Установка высоты ячейкам таблицы
 */
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cell_identifier = [NSString stringWithFormat:@"Cell_%@", [self fetchClass]];
    return [ProductCell cellHeight:tableView
                  cellIdentifier:cell_identifier
                           model:[self.fetchedResultsController objectAtIndexPath:indexPath]];
}

/**
 * Обработка нажатия по ячейке
 */
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    DPLogFast(@"");
    
    ProductModel *product = [self.fetchedResultsController objectAtIndexPath:indexPath];
    DPLogFast(@"product = %@", product);
    
    // ..
}

@end

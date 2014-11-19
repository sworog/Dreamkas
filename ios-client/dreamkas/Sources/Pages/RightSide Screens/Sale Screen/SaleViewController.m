//
//  SaleViewController.m
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SaleViewController.h"
#import "MoreButton.h"
#import "SaleItemCell.h"

@interface SaleViewController ()

@end

@implementation SaleViewController

#pragma mark - Инициализация

- (void)initialize
{
    // выключаем для контроллера массовое обновление и лимитированные запросы
    [self setPullDownActionEnabled:NO];
    [self setLimitedQueryEnabled:NO];
}

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    self.title = @"Чек";
    
    [self placeMoreBarButton];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    // ..
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    // ..
}

#pragma mark - Configuration Methods

- (void)placeMoreBarButton
{
    MoreButton *btn = [MoreButton buttonWithType:UIButtonTypeCustom];
    btn.frame = CGRectMake(0, 0, DefaultTopPanelHeight, DefaultTopPanelHeight);
    [btn setAccessibilityLabel:AI_TicketWindowPage_SearchButton];
    [btn addTarget:self action:@selector(moreButtonClicked) forControlEvents:UIControlEventTouchUpInside];
    UIBarButtonItem *right_btn = [[UIBarButtonItem alloc] initWithCustomView:btn];
    self.navigationItem.rightBarButtonItem = right_btn;
}

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (void)moreButtonClicked
{
    DPLogFast(@"");
}

#pragma mark - Методы CustomTableViewController

/**
 *  Kласс ячейки таблицы
 */
- (Class)cellClass
{
    return [SaleItemCell class];
}

/**
 *  Метод возвращает название класса, чьи экземпляры выбираются из БД и выводятся в таблице
 */
- (Class)fetchClass
{
    return [SaleItemModel class];
}

/**
 * Метод возвращает название параметра, по которому происходит сортировка при выборке из БД
 */
- (NSString*)fetchSortedField
{
    return @"submitDate";
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
    //    NSMutableArray *argument_array = [NSMutableArray new];
    //    NSMutableArray *format_array = [NSMutableArray new];
    //    NSPredicate *predicate = nil;
    //
    //    [format_array addObject:@"isActive = %@"];
    //    [argument_array addObject:@YES];
    //
    //    [format_array addObject:@"items.count > %@"];
    //    [argument_array addObject:@0];
    //
    //    // формируем предикат по полученным данным
    //    predicate = [NSPredicate predicateWithFormat:[format_array componentsJoinedByString:@" AND "]
    //                                   argumentArray:argument_array];
    return nil;
}

/**
 *  Метод, уведомляющий об окончании работы fetch-контроллера
 *  в связи с изменениями полей объектов БД
 */
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    [super controllerDidChangeContent:controller];
    
    [self.tableViewItem setContentOffset:CGPointMake(0, self.tableViewItem.contentSize.height - self.tableViewItem.height)
                                animated:YES];
}

/**
 *  Метод, инициирующий загрузку данных с сервера
 */
- (void)requestDataFromServer
{
    //    [super requestDataFromServer];
    //
    //    __weak typeof(self)weak_self = self;
    //    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
    //        __strong typeof(self)strong_self = weak_self;
    //
    //        if (error != nil) {
    //            [strong_self onMappingFailure:error];
    //            return;
    //        }
    //        [strong_self onMappingCompletion:data];
    //    }];
}

/**
 *  Установка высоты ячейкам таблицы
 */
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cell_identifier = [NSString stringWithFormat:@"Cell_%@", [self fetchClass]];
    return [SaleItemCell cellHeight:tableView
                     cellIdentifier:cell_identifier
                              model:[self.fetchedResultsController objectAtIndexPath:indexPath]];
}

/**
 * Обработка нажатия по ячейке
 */
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    DPLogFast(@"");
    
    SaleItemModel *model = [self.fetchedResultsController objectAtIndexPath:indexPath];
    [model increaseQuantity];
    DPLogFast(@"item = %@", model);
}

@end

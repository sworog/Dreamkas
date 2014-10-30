//
//  GroupsViewController.m
//  dreamkas
//
//  Created by sig on 28.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "GroupsViewController.h"
#import "SearchButton.h"
#import "BackButton.h"
#import "GroupCell.h"
#import "ProductsViewController.h"

@interface GroupsViewController ()

@end

@implementation GroupsViewController

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
    
    self.title = @"Все товары";
    
    [self placeSearchBarButton];
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

- (void)placeSearchBarButton
{
    SearchButton *btn = [SearchButton buttonWithType:UIButtonTypeCustom];
    btn.frame = CGRectMake(0, 0, DefaultTopPanelHeight, DefaultTopPanelHeight);
    [btn addTarget:self action:@selector(searchButtonClicked) forControlEvents:UIControlEventTouchUpInside];
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

- (void)searchButtonClicked
{
    DPLogFast(@"");
    
    [self performSegueWithIdentifier:GroupsToSearchSegueName sender:self];
}

#pragma mark - Методы CustomTableViewController

/**
 *  Kласс ячейки таблицы
 */
- (Class)cellClass
{
    return [GroupCell class];
}

/**
 *  Метод возвращает название класса, чьи экземпляры выбираются из БД и выводятся в таблице
 */
- (Class)fetchClass
{
    return [GroupModel class];
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
 *  Метод, инициирующий загрузку данных с сервера
 */
- (void)requestDataFromServer
{
    [super requestDataFromServer];
    
    __weak typeof(self)weak_self = self;
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
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
    return [GroupCell cellHeight:tableView
                  cellIdentifier:cell_identifier
                           model:[self.fetchedResultsController objectAtIndexPath:indexPath]];
}

#pragma mark - Методы StoryBoard Segue

/**
 *  Подготовительные действия перед выполнением перехода к следующему контроллеру
 */
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    DPLogFast(@"");
    
    if ([segue.identifier isEqualToString:GroupsToProductsSegueName]) {
        // обработка нажатия по ячейке таблицы
        NSIndexPath *index_path = [self.tableViewItem indexPathForSelectedRow];
        ProductsViewController *products_vc = segue.destinationViewController;
        [products_vc setGroupInstance:[self.fetchedResultsController objectAtIndexPath:index_path]];
    }
}

@end

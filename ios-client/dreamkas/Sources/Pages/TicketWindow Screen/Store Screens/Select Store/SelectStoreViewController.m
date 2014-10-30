//
//  SelectStoreViewController.m
//  dreamkas
//
//  Created by sig on 14.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SelectStoreViewController.h"
#import "StoreSelectionCell.h"

@interface SelectStoreViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;

@end

@implementation SelectStoreViewController

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
    
    // ..
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    [self.titleLabel setText:NSLocalizedString(@"select_store_title_name", nil)];
    [self.tableViewItem setAccessibilityLabel:AI_SelectStorePage_Table];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)closeButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    if ([[CurrentUser lastUsedStoreID] length]) {
        [self hideModalViewController];
    }
    else {
        [DialogHelper showError:@"Для работы необходимо выбрать магазин"];
    }
}

#pragma mark - Методы CustomTableViewController

/**
 *  Kласс ячейки таблицы
 */
- (Class)cellClass
{
    return [StoreSelectionCell class];
}

/**
 *  Метод возвращает название класса, чьи экземпляры выбираются из БД и выводятся в таблице
 */
- (Class)fetchClass
{
    return [StoreModel class];
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
    [NetworkManager requestStores:^(NSArray *data, NSError *error) {
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
    return [StoreSelectionCell cellHeight:tableView
                           cellIdentifier:cell_identifier
                                    model:[self.fetchedResultsController objectAtIndexPath:indexPath]];
}

/**
 * Обработка нажатия по ячейке
 */
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    DPLogFast(@"");
    
    StoreModel *store = [self.fetchedResultsController objectAtIndexPath:indexPath];
    [CurrentUser updateLastUsedStoreID:[store pk]];
    
    [self hideModalViewController];
}

@end

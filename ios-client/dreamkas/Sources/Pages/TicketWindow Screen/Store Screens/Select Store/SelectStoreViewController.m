//
//  SelectStoreViewController.m
//  dreamkas
//
//  Created by sig on 14.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SelectStoreViewController.h"

@interface SelectStoreViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;

@end

@implementation SelectStoreViewController

#pragma mark - Инициализация

- (void)initialize
{
    // включаем для контроллера массовое обновление и лимитированные запросы
    [self setPullDownActionEnabled:YES];
    [self setLimitedQueryEnabled:NO];
}

#pragma mark - View Lifecycle

- (void)configureLocalization
{
    [self.titleLabel setText:NSLocalizedString(@"select_store_title_name", nil)];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)closeButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self hideModalViewController];
}

#pragma mark - Методы CustomTableViewController

/**
 *  Kласс ячейки таблицы
 */
- (Class)cellClass
{
    return [CustomTableViewCell class];
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
    return 60;
}

/**
 * Настройка очередной ячейки таблицы с помощью полей соответствующего объекта
 */
- (void)configureCell:(UITableViewCell<CustomDataCellDelegate>*)cell
          atIndexPath:(NSIndexPath*)indexPath
           withObject:(NSManagedObject*)object
{
    StoreModel *sm = (StoreModel*)object;
    [[cell textLabel] setText:[sm name]];
}

@end

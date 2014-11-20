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

#define ControllerViewDefaultHeight 660

@interface SaleViewController () <KeyboardEventsListenerProtocol>

@property (nonatomic, weak) IBOutlet CustomLabel *infoMsgLabel;
@property (nonatomic, weak) IBOutlet CustomFilledButton *saleButton;
@property (nonatomic) ActionButton *clearButton;

@property (nonatomic, weak) IBOutlet UIView *tableFooterView;

@end

@implementation SaleViewController

#pragma mark - Инициализация

- (void)initialize
{
    self.title = @"Чек";
    
    // выключаем для контроллера массовое обновление и лимитированные запросы
    [self setPullDownActionEnabled:NO];
    [self setLimitedQueryEnabled:NO];
    [self becomeKeyboardEventsListener];
}

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.infoMsgLabel setFont:DefaultFont(12)];
    [self.infoMsgLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.54]];
    
    [self placeMoreBarButton];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    [self updateInfoMsgLabel];
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
    [self.infoMsgLabel setText:@"Товаров в чеке нет"];
    [self.clearButton setTitle:NSLocalizedString(@"clear_sale_button_title", nil) forState:UIControlStateNormal];
    [self.saleButton setTitle:NSLocalizedString(@"make_sale_button_title", nil) forState:UIControlStateNormal];
}

- (void)configureAccessibilityLabels
{
    // ..
}

/**
 * Обновление элементов слоя в зависимости от наличия контента под отображение
 */
- (void)updateInfoMsgLabel
{
    NSInteger count_of_elements = [self countOfItems];
    
    [self.infoMsgLabel setHidden:count_of_elements];
    
    [self.tableViewItem setHidden:!(count_of_elements)];
    [self.saleButton setEnabled:count_of_elements];
}

#pragma mark - Обработка пользовательского взаимодействия

- (void)moreButtonClicked
{
    DPLogFast(@"");
}

- (IBAction)clearButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [SaleItemModel MR_truncateAllInContext:[NSManagedObjectContext MR_defaultContext]];
    [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
}

- (IBAction)saleButtonClicked:(id)sender
{
    DPLogFast(@"");
}

#pragma mark - Методы KeyboardEventsListenerProtocol

- (void)keyboardDidAppear:(NSNotification *)notification
{
    DPLogFast(@"self.tableViewItem = %@", self.tableViewItem);
    
    CGRect keyboard_bounds;
    [[notification.userInfo valueForKey:UIKeyboardFrameBeginUserInfoKey] getValue:&keyboard_bounds];
    
    [self.view setHeight:ControllerViewDefaultHeight - CGRectGetHeight(keyboard_bounds) + DefaultTopPanelHeight];
    [self.tableViewItem reloadData];
}

- (void)keyboardWillDisappear:(NSNotification *)notification
{
    DPLogFast(@"");
    
    [self.view setHeight:ControllerViewDefaultHeight];
    [self.tableViewItem reloadData];
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
 *  Метод, уведомляющий об окончании работы fetch-контроллера
 *  в связи с изменениями полей объектов БД
 */
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    [super controllerDidChangeContent:controller];
    
    [self updateInfoMsgLabel];
    
    DPLogFast(@"size = %@", NSStringFromCGSize(self.tableViewItem.contentSize));
    
    // TODO: прокрутка вниз при добавлении
}

/**
 *  Метод, инициирующий загрузку данных с сервера
 */
- (void)requestDataFromServer
{
    // nothing to do here..
}

- (CGFloat)tableView:(UITableView *)tableView heightForFooterInSection:(NSInteger)section
{
    return DefaultSingleLineCellHeight;
}

- (UIView *)tableView:(UITableView *)tableView viewForFooterInSection:(NSInteger)section
{
    self.clearButton = [ActionButton buttonWithType:UIButtonTypeCustom];
    [self.clearButton setFrame:CGRectMake(0, 0, 320, 52)];
    [self.clearButton setTitle:NSLocalizedString(@"clear_sale_button_title", nil) forState:UIControlStateNormal];
    [self.clearButton addTarget:self action:@selector(clearButtonClicked:) forControlEvents:UIControlEventTouchUpInside];
    
    UIView *top_line = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 320, 1)];
    [top_line setBackgroundColor:DefaultLightGrayColor];
    [self.clearButton addSubview:top_line];
    
    UIView *bot_line = [[UIView alloc] initWithFrame:CGRectMake(0, self.clearButton.height-1, 320, 1)];
    [bot_line setBackgroundColor:DefaultLightGrayColor];
    [self.clearButton addSubview:bot_line];
    
    return self.clearButton;
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

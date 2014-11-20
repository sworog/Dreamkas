//
//  SearchViewController.m
//  dreamkas
//
//  Created by sig on 30.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SearchViewController.h"
#import "ProductSearchCell.h"
#import "SearchField.h"

#define RequiredSearchfieldValueLenght 3
#define ControllerViewDefaultHeight 660

typedef NS_ENUM(NSInteger, kInfoMessageType) {
    kInfoMessageTypeNone = 0,
    kInfoMessageTypeEmptyField,
    kInfoMessageTypeNoResults
};

@interface SearchViewController () <UITextFieldDelegate, KeyboardEventsListenerProtocol>
{
    NSMutableString *searchString;
}
@property (nonatomic) IBOutlet CustomLabel *infoMsgLabel;
@property (nonatomic) SearchField *searchField;

@end

@implementation SearchViewController

#pragma mark - Инициализация

- (void)initialize
{
    // выключаем для контроллера массовое обновление и лимитированные запросы
    [self setPullDownActionEnabled:NO];
    [self setLimitedQueryEnabled:NO];
    [self becomeKeyboardEventsListener];
    
    searchString = [NSMutableString string];
}

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.infoMsgLabel setFont:DefaultFont(12)];
    [self.infoMsgLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.54]];
    
    [self placeSearchField];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    [self.tableViewItem setHidden:YES];
    [self setInfoMessage:kInfoMessageTypeEmptyField];
    
    // делаем фокус на поле поиска, если не показывается боковое меню
    if ([self doesSidemenuShown] == NO)
        [self.searchField becomeFirstResponder];
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    // ..
}

#pragma mark - Configuration Methods

- (void)placeSearchField
{
    self.searchField = [[SearchField alloc] initWithFrame:CGRectZero];
    [self.searchField setDelegate:self];
    [self.searchField setWidth:600];
    [self.searchField setHeight:DefaultTopPanelHeight];
    [self.searchField setPlaceholder:NSLocalizedString(@"product_search_field_placeholder", nil)];
    [self.searchField setKeyboardType:UIKeyboardTypeDefault];
    [self.searchField setAutocapitalizationType:UITextAutocapitalizationTypeNone];
    [self.searchField setAccessibilityLabel:AI_SearchPage_SearchField];
    self.navigationItem.titleView = self.searchField;
}

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    // ..
}

- (void)setInfoMessage:(kInfoMessageType)type
{
    [self.infoMsgLabel setHidden:NO];
    
    switch ((kInfoMessageType)type) {
        case kInfoMessageTypeNoResults:
            [self.infoMsgLabel setText:NSLocalizedString(@"product_search_no_results_message", nil)];
            break;
            
        case kInfoMessageTypeEmptyField:
            [self.infoMsgLabel setText:NSLocalizedString(@"product_search_empty_message", nil)];
            break;
            
        default:
            [self.infoMsgLabel setText:@""];
            [self.infoMsgLabel setHidden:YES];
            break;
    }
}

- (BOOL)isSearchRequestValid
{
    return (searchString.length >= RequiredSearchfieldValueLenght);
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

#pragma mark - Методы UITextfield Delegate

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    DPLogFast(@"");
    
    NSMutableString *tmp = [NSMutableString stringWithString:textField.text];
    [tmp replaceCharactersInRange:range withString:string];
    
    searchString = [NSMutableString stringWithString:tmp];
    DPLogFast(@"searchString = %@", searchString);
    
    [UserDefaults setObject:searchString forKey:kSearchViewControllerSearchFieldKey];
    [UserDefaults synchronize];
    
    if ([self isSearchRequestValid]) {
        [self.tableViewItem setHidden:NO];
        [self setInfoMessage:kInfoMessageTypeNone];
        
        [self shouldReloadTableView];
        
    }
    else {
        [self.tableViewItem setHidden:YES];
        [self setInfoMessage:kInfoMessageTypeEmptyField];
    }
    
    return YES;
}

- (BOOL)textFieldShouldClear:(UITextField *)textField
{
    DPLogFast(@"");
    
    [self.tableViewItem setHidden:YES];
    [self setInfoMessage:kInfoMessageTypeEmptyField];
    
    return YES;
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    DPLogFast(@"");
    
    [textField resignFirstResponder];
    return YES;
}

#pragma mark - Методы CustomTableViewController

/**
 *  Kласс ячейки таблицы
 */
- (Class)cellClass
{
    return [ProductSearchCell class];
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
    
    // формируем выборку по названию
    [format_array addObject:@"name contains[cd] %@"];
    [argument_array addObject:searchString];
    
    // формируем выборку по штрих-коду
    [format_array addObject:@"barcode CONTAINS[cd] %@"];
    [argument_array addObject:searchString];
    
    // формируем выборку по sku
    [format_array addObject:@"sku CONTAINS[cd] %@"];
    [argument_array addObject:searchString];
    
    if (self.tableViewItem.isHidden) {
        // формируем предикат с заведомо пустой выдачей
        predicate = [NSPredicate predicateWithValue:NO];
    }
    else {
        // формируем предикат по полученным данным
        predicate = [NSPredicate predicateWithFormat:[format_array componentsJoinedByString:@" OR "]
                                       argumentArray:argument_array];
    }
    
    DPLogFast(@"fetch predicate = %@", predicate);
    return predicate;
}

/**
 *  Обработчик события выполнения запроса (в качестве параметра получает число выбранных записей)
 */
- (void)onFetchCompletion:(NSInteger)itemsCount
{
    [super onFetchCompletion:itemsCount];
    
    if ((itemsCount < 1) && [self isSearchRequestValid]) {
        [self setInfoMessage:kInfoMessageTypeNoResults];
    }
}

/**
 *  Метод, уведомляющий об окончании работы fetch-контроллера
 *  в связи с изменениями полей объектов БД
 */
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    [super controllerDidChangeContent:controller];
    
    if (([self countOfItems] > 0) && [self isSearchRequestValid]) {
        [self.tableViewItem setHidden:NO];
        [self setInfoMessage:kInfoMessageTypeNone];
    }
}

/**
 *  Метод, инициирующий загрузку данных с сервера
 */
- (void)requestDataFromServer
{
    if ([self isSearchRequestValid] == NO)
        return;
    
    [super requestDataFromServer];
    
    __weak typeof(self)weak_self = self;
    [NetworkManager requestProductsByQuery:searchString
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
    return [ProductSearchCell cellHeight:tableView
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
    
    SaleItemModel *item = [SaleItemModel saleItemForProduct:product];
    DPLogFast(@"item = %@", item);
    
    [self.tableViewItem deselectRowAtIndexPath:indexPath animated:NO];
}

@end

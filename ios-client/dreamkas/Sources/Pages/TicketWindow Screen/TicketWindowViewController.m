//
//  TicketWindowViewController.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "TicketWindowViewController.h"

@interface TicketWindowViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;
@property (nonatomic, weak) IBOutlet UITableView *tableView;

@end

@implementation TicketWindowViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.titleLabel setText:@""];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    if ([[CurrentUser lastUsedStoreID] length] < 1) {
        [self showViewControllerModally:ControllerById(SelectStoreViewControllerID)
                                segueId:TicketWindowToSelectStoreSegueName];
    }
    else {
        StoreModel *store = [StoreModel findByPK:[CurrentUser lastUsedStoreID]];
        if ([[store name] length] > 0)
            [self.titleLabel setText:[store name]];
    }
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    [self.tableView setAccessibilityLabel:AI_TicketWindowPage_Table];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)sidemenuButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    // TODO: показ бокового меню
}

@end

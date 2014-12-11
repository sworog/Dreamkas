//
//  SidemenuViewController.m
//  dreamkas
//
//  Created by sig on 21.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SidemenuViewController.h"

@interface SidemenuViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;

@property (nonatomic, weak) IBOutlet RaisedEmptyButton *changeStoreButton;

@end

@implementation SidemenuViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.titleLabel setText:@""];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    if ([[CurrentUser lastUsedStoreID] length]) {
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
    [self.changeStoreButton setAccessibilityLabel:AI_Sidemenu_ChangeStoreButton];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)changeStoreButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [(AbstractViewController*)self.parentViewController showViewControllerModally:ControllerById(SelectStoreViewControllerID)];
}

- (IBAction)logoutButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [CurrentUser resetLastUsedAuthData];
    
    [self.parentViewController.navigationController popToRootViewControllerAnimated:YES];
}

@end

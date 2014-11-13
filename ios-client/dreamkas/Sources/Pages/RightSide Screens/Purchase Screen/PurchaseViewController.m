//
//  PurchaseViewController.m
//  dreamkas
//
//  Created by sig on 13.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PurchaseViewController.h"
#import "MoreButton.h"

@interface PurchaseViewController ()

@end

@implementation PurchaseViewController

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

@end

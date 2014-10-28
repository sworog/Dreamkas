//
//  GroupsViewController.m
//  dreamkas
//
//  Created by sig on 28.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "GroupsViewController.h"

@interface GroupsViewController ()

@property (nonatomic, weak) IBOutlet UIButton *searchButton;

@end

@implementation GroupsViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.searchButton.titleLabel setFont:DefaultAwesomeFont(20.f)];
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

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)searchButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    // ..
}

@end

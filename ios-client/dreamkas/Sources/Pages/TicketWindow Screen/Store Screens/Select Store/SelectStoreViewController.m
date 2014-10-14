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

@end

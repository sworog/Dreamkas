//
//  PaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PaymentViewController.h"

@implementation PaymentViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self initCloseButton];
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    [self setTitle:NSLocalizedString(@"payment_page_title", nil)];
}

- (void)configureAccessibilityLabels
{
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)nextButtonClicked:(id)sender
{
    DPLogFast(@"");
}

@end

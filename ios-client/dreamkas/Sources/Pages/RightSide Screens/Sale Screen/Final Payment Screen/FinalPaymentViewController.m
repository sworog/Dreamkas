//
//  FinalPaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "FinalPaymentViewController.h"

@interface FinalPaymentViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;

@end

@implementation FinalPaymentViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    [self setTitle:NSLocalizedString(@"final_payment_page_title", nil)];
}

- (void)configureAccessibilityLabels
{
    // ..
}

@end

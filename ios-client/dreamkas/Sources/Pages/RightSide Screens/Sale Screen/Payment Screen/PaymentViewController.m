//
//  PaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PaymentViewController.h"

@interface PaymentViewController () <UITextFieldDelegate>

@property (nonatomic, weak) IBOutlet CustomEmptyButton *payButton, *payByUsingCard;
@property (nonatomic, weak) IBOutlet CustomLabel *firstLabel, *secondLabel;
@property (nonatomic, weak) IBOutlet CustomTextField *paymentSumField;

@end

@implementation PaymentViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self initCloseButton];
    
    self.firstLabel.font =
    self.secondLabel.font = DefaultFont(12);
    self.firstLabel.textColor =
    self.secondLabel.textColor = [DefaultBlackColor colorWithAlphaComponent:0.54];
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    NSString *title = [NSString stringWithFormat:@"%@ ₽", [CountSaleHelper countSaleItemsTotalSum]];
    [self setTitle:title];
    
    [self.payButton setTitle:NSLocalizedString(@"payment_page_pay_button", nil) forState:UIControlStateNormal];
    [self.payByUsingCard setTitle:NSLocalizedString(@"payment_page_pay_by_using_card_button", nil) forState:UIControlStateNormal];
    
    [self.firstLabel setText:NSLocalizedString(@"payment_page_payment_text", nil)];
    [self.secondLabel setText:NSLocalizedString(@"payment_page_card_payment_text", nil)];
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

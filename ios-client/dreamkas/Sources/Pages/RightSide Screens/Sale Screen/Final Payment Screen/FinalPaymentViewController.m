//
//  FinalPaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "FinalPaymentViewController.h"

@interface FinalPaymentViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *changeLabel, *changeFromTotalLabel;
@property (nonatomic, weak) IBOutlet UILabel *checkCircle;
@property (nonatomic, weak) IBOutlet RaisedButton *theNewSaleButton;

@end

@implementation FinalPaymentViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    unichar ch = 0xf05d;
    [self.checkCircle setFont:DefaultAwesomeFont(40)];
    [self.checkCircle setTextColor:DefaultLightCyanColor];
    [self.checkCircle setText:[NSString stringWithFormat:@"%C", ch]];
    
    [self.changeLabel setFont:DefaultFont(24)];
    [self.changeLabel setTextColor:DefaultBlackColor];
    
    [self.changeFromTotalLabel setFont:DefaultMediumFont(14)];
    [self.changeFromTotalLabel setTextColor:DefaultLightGrayColor];
    
    // настройка по логике
    if (false) {
        [self.changeLabel setText:NSLocalizedString(@"final_payment_page_payed_by_card_title", nil)];
        [self.changeFromTotalLabel setHidden:YES];
    }
    else {
        [self.changeLabel setText:@"7,00 P"];
        [self.changeFromTotalLabel setHidden:NO];
        [self.changeFromTotalLabel setText:@"Сдача с 1000 Р"];
    }
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    [self setTitle:@""];
    
    [self.theNewSaleButton setTitle:NSLocalizedString(@"final_payment_page_new_sale_button", nil) forState:UIControlStateNormal];
}

- (void)configureAccessibilityLabels
{
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)theNewSaleButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self closeButtonClicked];
}

@end

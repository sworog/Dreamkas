//
//  FinalPaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "FinalPaymentViewController.h"

@interface FinalPaymentViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *changeLabel, *changeFromTotalLabel, *paidByCardLabel;
@property (nonatomic, weak) IBOutlet UILabel *checkCircle;
@property (nonatomic, weak) IBOutlet RaisedFilledButton *theNewSaleButton;

@end

@implementation FinalPaymentViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    unichar ch = 0xf05d;
    [self.checkCircle setFont:DefaultAwesomeFont(120)];
    [self.checkCircle setTextColor:DefaultSuperLightCyanColor];
    [self.checkCircle setText:[NSString stringWithFormat:@"%C", ch]];
    
    [self.changeFromTotalLabel setFont:DefaultFont(14)];
    [self.changeFromTotalLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.54]];
    
    [self.paidByCardLabel setFont:DefaultLightFont(34)];
    [self.paidByCardLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.87]];
    
    // настройка окружения
    if (false) {
        [self.paidByCardLabel setHidden:NO];
        
        [self.changeLabel setHidden:YES];
        [self.changeFromTotalLabel setHidden:YES];
    }
    else {
        [self.paidByCardLabel setHidden:YES];
        
        [self.changeLabel setHidden:NO];
        [self.changeFromTotalLabel setHidden:NO];
        
        NSNumber *change_value = @(125.90);
        NSNumber *total_value = @(1230.00);
        
        PriceNumberFormatter *formatter = [PriceNumberFormatter new];
        NSMutableString *change_str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:change_value]];
        NSMutableAttributedString *m_attr_str = [[NSMutableAttributedString alloc] initWithString:change_str
                                                                                       attributes:@{NSFontAttributeName:DefaultLightFont(34),
                                                                                                    NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.87]}];
        [m_attr_str setAttributes:@{NSFontAttributeName:DefaultMediumFont(34),
                                    NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.87]}
                            range:[change_str rangeOfString:@"₽" options:NSCaseInsensitiveSearch]];
        
        [self.changeLabel setAttributedText:m_attr_str];
        
        NSMutableString *total_str = [NSMutableString stringWithFormat:@"%@ %@", NSLocalizedString(@"final_payment_page_change_from", nil),
                                      [formatter stringFromNumber:total_value]];
        [self.changeFromTotalLabel setText:total_str];
    }
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    // скрываем кнопку "Назад"
    self.navigationItem.leftBarButtonItems = nil;
    self.navigationItem.hidesBackButton = YES;
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    [self setTitle:@""];
    
    [self.paidByCardLabel setText:NSLocalizedString(@"final_payment_page_payed_by_card_title", nil)];
    
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
    
    // TODO: сброс чека в локальной БД
    
    [self closeButtonClicked];
}

@end

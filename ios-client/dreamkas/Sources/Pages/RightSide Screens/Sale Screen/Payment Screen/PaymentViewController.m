//
//  PaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PaymentViewController.h"

@interface PaymentViewController () <UITextFieldDelegate>

@property (nonatomic, weak) IBOutlet RaisedEmptyButton *payButton, *payByUsingCard;
@property (nonatomic, weak) IBOutlet CustomLabel *firstLabel, *secondLabel;
@property (nonatomic, weak) IBOutlet StaticTextField *paymentSumField;

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
    
    [self.payButton setEnabled:NO];
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

#pragma mark - Методы UITextfield Delegate

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    DPLogFast(@"");
    DPLogFast(@"string = %@", string);
    
    NSMutableString *new_string = [NSMutableString stringWithString:textField.text];
    
//    // запрещаем ввод не разрешенных символов
//    NSCharacterSet *not_allowed_charactes = [[NSCharacterSet characterSetWithCharactersInString:@"0123456789.,"] invertedSet];
//    if ([string rangeOfCharacterFromSet:not_allowed_charactes].location != NSNotFound) {
//        return NO;
//    }
    
    [new_string replaceCharactersInRange:range withString:string];
    if ([ValidationHelper isPriceValid:new_string] == NO) {
//        return NO;
    }
    
    DPLogFast(@"new_string = %@", new_string);
    [self.payButton setEnabled:YES];
    return YES;
}

- (BOOL)textFieldShouldClear:(UITextField *)textField
{
    DPLogFast(@"");
    
    [self.payButton setEnabled:NO];
    
    return YES;
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    DPLogFast(@"");
    
    [textField resignFirstResponder];
    return YES;
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)payButtonClicked:(id)sender
{
    DPLogFast(@"");
}

- (IBAction)payByUsingCardClicked:(id)sender
{
    DPLogFast(@"");
}

@end

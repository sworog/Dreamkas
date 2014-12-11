//
//  PaymentViewController.m
//  dreamkas
//
//  Created by sig on 26.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PaymentViewController.h"
#import "FinalPaymentViewController.h"

@interface PaymentViewController () <UITextFieldDelegate>

@property (nonatomic, weak) IBOutlet RaisedFilledButton *payButton;
@property (nonatomic, weak) IBOutlet RaisedEmptyButton *payByUsingCard;
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
    NSString *title = [NSString stringWithFormat:@"%@ ₽", [CountSaleHelper calculateFinalPriceStringValue]];
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
    
    NSMutableString *new_string = [NSMutableString stringWithString:textField.text];
    [new_string replaceCharactersInRange:range withString:string];
    
    // валидируем ввод
    if ([ValidationHelper isPriceValid:new_string] == NO) {
        return ([new_string length] == 0);
    }
    
    [self.payButton setEnabled:[CountSaleHelper isThereIsEnoughProvidedPayment:new_string]];
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

- (IBAction)viewTouched:(id)sender
{
    DPLogFast(@"");
    
    [self.view endEditing:YES];
}

- (IBAction)payButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self viewTouched:nil];
    [self showLoading];
    
    __weak typeof(self)weak_self = self;
    [NetworkManager sendPayment:self.paymentSumField.text
                    paymentType:kPaymentTypeCash
                   onCompletion:^(AbstractModel *object, NSError *error)
    {
        __strong typeof(self)strong_self = weak_self;
        [strong_self hideLoading];
        
        if (error != nil) {
            [DialogHelper showRequestError];
            return;
        }
        
        // чистим чек
        [SaleItemModel MR_truncateAllInContext:[NSManagedObjectContext MR_defaultContext]];
        
        // переходим к финальному экрану
        FinalPaymentViewController *final_vc = ControllerById(FinalPaymentViewControllerID);
        [final_vc setReferenceModel:(SaleModel *)object];
        [strong_self.navigationController pushViewController:final_vc animated:YES];
    }];
}

- (IBAction)payByUsingCardClicked:(id)sender
{
    DPLogFast(@"");
    
    [self viewTouched:nil];
    [self showLoading];
    
    __weak typeof(self)weak_self = self;
    [NetworkManager sendPayment:@""
                    paymentType:kPaymentTypeCard
                   onCompletion:^(AbstractModel *object, NSError *error)
     {
         __strong typeof(self)strong_self = weak_self;
         [strong_self hideLoading];
         
         if (error != nil) {
             [DialogHelper showRequestError];
             return;
         }
         
         // чистим чек
         [SaleItemModel MR_truncateAllInContext:[NSManagedObjectContext MR_defaultContext]];
         
         // переходим к финальному экрану
         FinalPaymentViewController *final_vc = ControllerById(FinalPaymentViewControllerID);
         [final_vc setReferenceModel:(SaleModel *)object];
         [strong_self.navigationController pushViewController:final_vc animated:YES];
     }];
}

@end

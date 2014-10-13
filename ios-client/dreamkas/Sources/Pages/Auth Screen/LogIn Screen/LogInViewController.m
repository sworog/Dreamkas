//
//  LogInViewController.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "LogInViewController.h"

#define PasswordMinLength 3

@interface LogInViewController () <UITextFieldDelegate>

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;
@property (nonatomic, weak) IBOutlet CustomFilledButton *logInButton;

@property (nonatomic, weak) IBOutlet UIView *containerView;

@property (nonatomic, weak) IBOutlet CustomTextField *loginField;
@property (nonatomic, weak) IBOutlet CustomTextField *passwordField;

@end

@implementation LogInViewController

#pragma mark - View Lifecycle

- (void)configureLocalization
{
    [self.titleLabel setText:NSLocalizedString(@"log_in_title_name", nil)];
    [self.logInButton setTitle:NSLocalizedString(@"log_in_button_title", nil) forState:UIControlStateNormal];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.logInButton setEnabled:NO];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)closeButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self hideModalViewController];
}

- (IBAction)logInButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self.view endEditing:YES];
    
    // выполняем авторизацию на сервере
    
    [self showLoading];
    __weak typeof(self)weak_self = self;
    
    [NetworkManager authWithLogin:self.loginField.text
                         password:self.passwordField.text
                     onCompletion:^(NSDictionary *data, NSError *error)
     {
         __strong typeof(self)strong_self = weak_self;
         [strong_self hideLoading];
         
         if (error == nil) {
             // если авторизация прошла успешно - запоминаем данные пользователя
             [CurrentUser updateLastUsedLogin:API_TEST_LOGIN
                             lastUsedPassword:API_TEST_PWD];
             
             [strong_self performSegueWithIdentifier:LogInToTicketWindowSegueName sender:self];
         }
         else {
             [DialogHelper showRequestError];
         }
     }];
}

- (IBAction)forgetButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self.loginField setText:API_TEST_LOGIN];
    [self.passwordField setText:API_TEST_PWD];
    [self.logInButton setEnabled:YES];
}

#pragma mark - Методы UITextField Delegate

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    DPLogFast(@"");
    
    NSMutableString *new_string = [NSMutableString stringWithString:textField.text];
    [new_string replaceCharactersInRange:range withString:string];
    
    if ([textField isEqual:self.loginField]) {
        if (([ValidationHelper isEmailValid:new_string])
            && (self.passwordField.text.length >= PasswordMinLength))
        {
            [self.logInButton setEnabled:YES];
        }
        else {
            [self.logInButton setEnabled:NO];
        }
    }
    else {
        if (([ValidationHelper isEmailValid:self.loginField.text])
            && (new_string.length >= PasswordMinLength))
        {
            [self.logInButton setEnabled:YES];
        }
        else {
            [self.logInButton setEnabled:NO];
        }
    }
    
    return YES;
}

@end

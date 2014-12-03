//
//  LogInViewController.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "LogInViewController.h"
#import "CustomNavigationBar.h"
#import "ModalViewController.h"

#define PasswordMinLength 6

@interface LogInViewController () <UITextFieldDelegate>

@property (nonatomic, weak) IBOutlet DynamicTextField *loginField;
@property (nonatomic, weak) IBOutlet DynamicTextField *passwordField;

@property (nonatomic, weak) IBOutlet FlatButton *forgetButton;

@property (nonatomic) RaisedFilledButton *logInButton;

@end

@implementation LogInViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self initCloseButton];
    [self initLogInButton];
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    [self setTitle:NSLocalizedString(@"log_in_title_name", nil)];
    
    [self.loginField setAttributedPlaceholder:NSLocalizedString(@"login_page_login_field_placeholder", nil)];
    [self.passwordField setAttributedPlaceholder:NSLocalizedString(@"login_page_passward_field_placeholder", nil)];
    
    [self.forgetButton setTitle:NSLocalizedString(@"login_page_forget_button", nil) forState:UIControlStateNormal];
}

- (void)configureAccessibilityLabels
{
    [self.loginField setAccessibilityLabel:AI_LogInPage_LoginField];
    [self.passwordField setAccessibilityLabel:AI_LogInPage_PwdField];
    
    [self.forgetButton setAccessibilityLabel:AI_LogInPage_ForgetButton];
}

/**
 * Инициализация кнопки Close
 */
- (void)initLogInButton
{
    self.logInButton = [RaisedFilledButton buttonWithType:UIButtonTypeCustom];
    self.logInButton.frame = CGRectMake(0, 0, 100, DefaultButtonHeight);
    
    [self.logInButton setAccessibilityLabel:AI_LogInPage_LogInButton];
    [self.logInButton setTitle:NSLocalizedString(@"log_in_button_title", nil) forState:UIControlStateNormal];
    [self.logInButton addTarget:self action:@selector(logInButtonClicked:) forControlEvents:UIControlEventTouchUpInside];
    
    UIBarButtonItem *right_btn = [[UIBarButtonItem alloc] initWithCustomView:self.logInButton];
    UIBarButtonItem *right_btn_space = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFixedSpace target:nil action:nil];
    right_btn_space.width = 8.f;
    self.navigationItem.rightBarButtonItems = @[right_btn_space, right_btn];
    
    [self.logInButton setEnabled:NO];
}

#pragma mark - Обработка пользовательского взаимодействия

- (void)logInButtonClicked:(id)sender
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
             [CurrentUser updateLastUsedLogin:self.loginField.text
                             lastUsedPassword:self.passwordField.text];

             // скрываем контейнер модального окна и переходим к контроллеру кассы
             ModalViewController *modal_vc = (ModalViewController *)self.navigationController.parentViewController;
             [modal_vc hideContainerView:^(BOOL finished) {
                 [modal_vc.navigationController pushViewController:ControllerById(TicketWindowViewControllerID) animated:YES];
             }];
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

- (IBAction)viewTouched:(id)sender
{
    DPLogFast(@"");
    
    [self.view endEditing:YES];
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

- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    DPLogFast(@"");
    
    if ([textField isEqual:self.loginField]) {
        [self.passwordField becomeFirstResponder];
    }
    else {
        [self.view endEditing:YES];
    }
    
    return YES;
}

@end

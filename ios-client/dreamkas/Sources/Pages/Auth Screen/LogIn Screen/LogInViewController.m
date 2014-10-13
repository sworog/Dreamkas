//
//  LogInViewController.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "LogInViewController.h"

@interface LogInViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;
@property (nonatomic, weak) IBOutlet CustomFilledButton *logInButton;

@property (nonatomic, weak) IBOutlet UIView *containerView;

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
    
    // ..
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
    
    [self showLoading];
    __weak typeof(self)weak_self = self;
    
    [NetworkManager authWithLogin:API_TEST_LOGIN
                         password:API_TEST_PWD
                     onCompletion:^(NSDictionary *data, NSError *error)
     {
         __strong typeof(self)strong_self = weak_self;
         [strong_self hideLoading];
         
         if (error == nil) {
             // если авторизация прошла успешно - запоминаем данные пользователя
             [CurrentUser updateLastUsedLogin:API_TEST_LOGIN
                             lastUsedPassword:API_TEST_PWD];
             
             //[self performSegueWithIdentifier:AuthToTicketWindowScreenSegueName sender:self];
         }
         else {
             [DialogHelper showRequestError];
         }
     }];
}

@end

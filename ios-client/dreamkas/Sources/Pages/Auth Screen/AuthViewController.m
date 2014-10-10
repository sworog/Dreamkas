//
//  AuthViewController.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AuthViewController.h"
#import "CustomFilledButton.h"
#import "CustomEmptyButton.h"

@interface AuthViewController ()

@property (nonatomic, weak) IBOutlet CustomEmptyButton *logInButton;
@property (nonatomic, weak) IBOutlet CustomFilledButton *signUpButton;

@end

@implementation AuthViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self.logInButton setTitle:NSLocalizedString(@"log_in_button_title", nil) forState:UIControlStateNormal];
    [self.signUpButton setTitle:NSLocalizedString(@"sign_up_button_title", nil) forState:UIControlStateNormal];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)signUpButtonClicked:(id)sender
{
    DPLogFast(@"");
}

- (IBAction)logInButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [NetworkManager authWithLogin:API_TEST_LOGIN
                         password:API_TEST_PWD
                     onCompletion:^(NSDictionary *data, NSError *error)
     {
         if (error == nil) {
             // если авторизация прошла успешно - запоминаем данные пользователя
             [CurrentUser updateLastUsedLogin:API_TEST_LOGIN
                             lastUsedPassword:API_TEST_PWD];
             
             [self performSegueWithIdentifier:AuthToTicketWindowScreenSegueName sender:self];
         }
     }];
}

@end

//
//  AuthViewController.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AuthViewController.h"

@interface AuthViewController ()

@property (nonatomic, weak) IBOutlet RaisedFilledButton *signUpButton;
@property (nonatomic, weak) IBOutlet RaisedEmptyButton *logInButton;

@end

@implementation AuthViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    [self.logInButton setTitle:NSLocalizedString(@"log_in_button_title", nil) forState:UIControlStateNormal];
    [self.signUpButton setTitle:NSLocalizedString(@"sign_up_button_title", nil) forState:UIControlStateNormal];
}

- (void)configureAccessibilityLabels
{
    [self.logInButton setAccessibilityLabel:AI_AuthPage_LogInButton];
    [self.signUpButton setAccessibilityLabel:AI_AuthPage_SignInButton];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)signUpButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self showViewControllerModally:ControllerById(SignInViewControllerID)];
}

- (IBAction)logInButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self showViewControllerModally:ControllerById(LogInViewControllerID)];
}

@end

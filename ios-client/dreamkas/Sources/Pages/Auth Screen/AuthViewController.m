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

#pragma mark - View Lifecycle

- (void)configureLocalization
{
    [self.logInButton setTitle:NSLocalizedString(@"log_in_button_title", nil) forState:UIControlStateNormal];
    [self.signUpButton setTitle:NSLocalizedString(@"sign_up_button_title", nil) forState:UIControlStateNormal];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)signUpButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self showViewControllerModally:ControllerById(SignInViewControllerID)
                            segueId:AuthToSignInScreenSegueName];
}

- (IBAction)logInButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self showViewControllerModally:ControllerById(LogInViewControllerID)
                            segueId:AuthToLogInScreenSegueName];
}

@end

//
//  SignInViewController.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SignInViewController.h"

@interface SignInViewController ()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;
@property (nonatomic, weak) IBOutlet CustomFilledButton *signInButton;

@property (nonatomic, weak) IBOutlet UIView *containerView;

@end

@implementation SignInViewController

#pragma mark - View Lifecycle

- (void)configureLocalization
{
    [self.titleLabel setText:NSLocalizedString(@"sign_in_title_name", nil)];
    [self.signInButton setTitle:NSLocalizedString(@"sign_in_button_title", nil) forState:UIControlStateNormal];
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

- (IBAction)signInButtonClicked:(id)sender
{
    DPLogFast(@"");
}

@end

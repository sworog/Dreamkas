//
//  IntroViewController.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "IntroViewController.h"
#import "CustomLabel.h"

static const CGFloat TimeoutBeforeStart = 2.0f;

@interface IntroViewController()

@property (nonatomic, weak) IBOutlet CustomLabel *titleLabel;

@end

@implementation IntroViewController

#pragma mark - View Lifecicle

- (void)configureLocalization
{
    [self.titleLabel setText:NSLocalizedString(@"intro_screen_title", nil)];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // запускаем логику по таймауту
    [NSTimer scheduledTimerWithTimeInterval:TimeoutBeforeStart target:self
                                   selector:@selector(startLogic) userInfo:nil repeats:NO];
}

#pragma mark - Основная логика работы

/**
 *  Переход к основному экрану приложения
 */
- (void)startLogic
{
    if ([CurrentUser hasActualAuthData] == NO) {
        [self performSegueWithIdentifier:IntroToAuthScreenSegueName sender:self];
        return;
    }
    
    [self showLoading];
    __weak typeof(self)weak_self = self;
    
    [NetworkManager authWithLogin:[CurrentUser lastUsedLogin]
                         password:[CurrentUser lastUsedPassword]
                     onCompletion:^(NSDictionary *data, NSError *error)
     {
         __strong typeof(self)strong_self = weak_self;
         [strong_self hideLoading];
         
         if (error == nil) {
             // если авторизация прошла успешно - переходим к кассе
             [self performSegueWithIdentifier:IntroToCashierPwdScreenSegueName sender:self];
         }
         else {
             [DialogHelper showRequestError];
         }
     }];
}

@end

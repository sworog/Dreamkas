//
//  AbstractViewController.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

#define LOG_ON 1

@interface AbstractViewController () <MBProgressHUDDelegate>
{
    MBProgressHUD *indicator;
}
@end

@implementation AbstractViewController

#pragma mark - Инициализация

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        [self initialize];
    }
    return self;
}

- (id)initWithCoder:(NSCoder *)aDecoder
{
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self initialize];
    }
    
    return self;
}

/**
 * Обобщенный метод инициализации контроллера
 */
- (void)initialize
{
    // override me if needed
}

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self configureBackground];
    [self configureLocalization];
    [self configureAccessibilityLabels];
}

- (void)didReceiveMemoryWarning
{
    DPLog(LOG_ON, @"");
    
    [super didReceiveMemoryWarning];
}

#pragma mark - Базовые методы настройки контроллера

/**
 * Установка локализованных строк
 */
- (void)configureLocalization
{
    // override me
}

/**
 * Установка идентификаторов доступа к визуальным элементам
 */
- (void)configureAccessibilityLabels
{
    // override me
}

/**
 * Настройка фона экрана
 */
- (void)configureBackground
{
    self.view.backgroundColor = DefaultWhiteColor;
}

/**
 * Обработка нажатия по кнопке возврата к предыдущему экрану
 */
- (void)backButtonClicked
{
    [self.navigationController popViewControllerAnimated:YES];
}

#pragma mark - Индикация загрузки данных

- (void)showLoading
{
    [self showLoadingWithText:nil];
}

- (void)showLoadingWithText:(NSString*)text
{
    if (indicator == nil) {
        indicator = [[MBProgressHUD alloc] initWithView:self.view];
        indicator.delegate = self;
        indicator.dimBackground = NO;
        indicator.square = YES;
        [self.view addSubview:indicator];
        [self.view bringSubviewToFront:indicator];
        
    }
    if (text)
        indicator.labelText = text;
    [indicator show:YES];
}

- (void)hideLoading
{
    if (indicator)
        [indicator hide:YES];
}

- (void)hideLoadingAfterDelay:(NSTimeInterval)delay
{
    if (indicator)
        [indicator hide:YES afterDelay:delay];
}

- (void)changeLoadingText:(NSString*)text
{
    if ((indicator == nil) || ([text length] < 1))
        return;
    
    indicator.labelText = text;
}

#pragma mark - Методы MBProgressHUD Delegate

/**
 * Called after the HUD was fully hidden from the screen.
 */
- (void)hudWasHidden:(MBProgressHUD *)hud
{
    [indicator removeFromSuperview];
    indicator = nil;
}

#pragma mark - Поддерживаемые ориентации экрана

- (BOOL)shouldAutorotate
{
    return YES;
}

- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskLandscapeRight | UIInterfaceOrientationMaskLandscapeLeft;
}

//- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
//{
//    return UIInterfaceOrientationLandscapeRight;
//}

@end

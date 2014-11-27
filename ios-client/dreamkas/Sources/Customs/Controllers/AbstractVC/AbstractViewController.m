//
//  AbstractViewController.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"
#import "ModalViewController.h"
#import "BackButton.h"
#import "CloseButton.h"

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

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    [self configureNavbar];
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
 * Настройка навбара
 */
- (void)configureNavbar
{
    if ([self.navigationController isNavigationBarHidden]) {
        DPLog(LOG_ON, @"Navigation Bar is Hidden");
        return;
    }
    
    // устанавливаем (по необходимости) кнопку возврата назад
    if ([self.navigationController.viewControllers count] != 1) {
        [self initBackButton];
    }
    
    // something else ..
}

/**
 * Инициализация кнопки Back
 */
- (void)initBackButton
{
    BackButton *back_btn = [BackButton buttonWithType:UIButtonTypeCustom];
    back_btn.frame = CGRectMake(0, 0, DefaultTopPanelHeight, DefaultTopPanelHeight);
    [back_btn setAccessibilityLabel:AI_Common_NavbarBackButton];
    [back_btn addTarget:self action:@selector(backButtonClicked) forControlEvents:UIControlEventTouchUpInside];
    
    UIBarButtonItem *left_btn = [[UIBarButtonItem alloc] initWithCustomView:back_btn];
    self.navigationItem.leftBarButtonItem = left_btn;
}

/**
 * Обработка нажатия по кнопке возврата к предыдущему экрану
 */
- (void)backButtonClicked
{
    [self.navigationController popViewControllerAnimated:YES];
}

/**
 * Инициализация кнопки Close
 */
- (void)initCloseButton
{
    CloseButton *close_btn = [CloseButton buttonWithType:UIButtonTypeCustom];
    close_btn.frame = CGRectMake(0, 0, DefaultTopPanelHeight, DefaultTopPanelHeight);
    [close_btn setAccessibilityLabel:AI_Common_NavbarCloseButton];
    [close_btn addTarget:self action:@selector(closeButtonClicked) forControlEvents:UIControlEventTouchUpInside];
    
    UIBarButtonItem *left_btn = [[UIBarButtonItem alloc] initWithCustomView:close_btn];
    self.navigationItem.leftBarButtonItem = left_btn;
}

/**
 * Обработка нажатия по кнопке закрытия
 */
- (void)closeButtonClicked
{
    //
    // redefine me if needed
    //
    
    [self hideModalViewController:(ModalViewController *)self.navigationController.parentViewController];
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

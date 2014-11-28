//
//  ModalViewController.m
//  dreamkas
//
//  Created by sig on 27.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ModalViewController.h"

#define HiddenContainerYPosition    770.f
#define ShownContainerYPosition     0.f

@interface ModalViewController ()

@property (nonatomic, weak) IBOutlet UIView *containerView;

@property (nonatomic) UINavigationController *containerNavigationController;
@property (nonatomic) UIViewController *viewControllerForNavigationController;

@end

@implementation ModalViewController

#pragma mark - View Lifecycle

- (void)viewDidLayoutSubviews
{
    [super viewDidLayoutSubviews];
    
    // сдвигаем контейнер в исходную нижнюю позицию
    [self.containerView setY:HiddenContainerYPosition];
    
    // установка контроллера навигации в контейнер
    UINavigationController *nc = ControllerById(ModalNavigationControllerID);
    [nc.view setWidth:self.containerView.width];
    [nc.view setHeight:self.containerView.height];
    [self addChildViewController:nc];
    [self.containerView addSubview:nc.view];
    [nc didMoveToParentViewController:self];
    
    // подменяем rootViewController у контроллера навигации в контейнере
    self.containerNavigationController = nc;
    [self.containerNavigationController setViewControllers:@[self.viewControllerForNavigationController]];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    // вызываем вручную (!?idk)
    [self.viewControllerForNavigationController viewWillAppear:animated];
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    // анимированно показываем контейнер с контроллером
    [self showContainerView];
    
    // вызываем вручную (!?idk)
    [self.viewControllerForNavigationController viewDidAppear:animated];
}

- (void)viewWillDisappear:(BOOL)animated
{
    [super viewWillDisappear:animated];
    
    // вызываем вручную (!?idk)
    [self.viewControllerForNavigationController viewWillDisappear:animated];
}

- (void)viewDidDisappear:(BOOL)animated
{
    [super viewDidDisappear:animated];
    
    // вызываем вручную (!?idk)
    [self.viewControllerForNavigationController viewDidDisappear:animated];
}

#pragma mark - Работа с контейнером контроллера

/**
 * Установка контроллера в контейнер модального контроллера
 */
- (void)placeViewController:(UIViewController *)viewController
{
    DPLogFast(@"");
    
    self.viewControllerForNavigationController = viewController;
}

/**
 * Анимированный показ контекстного контроллера с выездом контейнера снизу вверх
 */
- (void)showContainerView
{
    DPLogFast(@"");
    
    [UIView animateWithDuration:KeyboardAnimationDuration animations:^{
        [self.containerView setY:ShownContainerYPosition];
    } completion:^(BOOL finished) {
        DPLogFast(@"finished showContainerView");
    }];
}

/**
 * Анимированное скрытие контекстного контроллера со съездом контейнера сверху вниз
 */
- (void)hideContainerView:(void (^)(BOOL finished))completion
{
    DPLogFast(@"");
    
    [UIView animateWithDuration:KeyboardAnimationDuration animations:^{
        [self.containerView setY:HiddenContainerYPosition];
    } completion:^(BOOL finished) {
        DPLogFast(@"finished hideContainerView");
        if (completion)
            completion(finished);
    }];
}

@end

//
//  AbstractViewController+Sidemenu.m
//  dreamkas
//
//  Created by sig on 21.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController+Sidemenu.h"
#import <objc/runtime.h>
#import "SidemenuViewController.h"

@implementation AbstractViewController (Sidemenu)

@dynamic sidemenuContainerView;
@dynamic sidemenuOverlayView;

#pragma mark - Работа в рантайме

/*
 * Кастомный сеттер для свойства в категории
 */
- (void)setSidemenuContainerView:(UIView *)view
{
    objc_setAssociatedObject(self, @selector(sidemenuContainerView), view, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
}

/*
 * Кастомный геттер для свойства в категории
 */
- (UIView*)sidemenuContainerView
{
    return objc_getAssociatedObject(self, @selector(sidemenuContainerView));
}

/*
 * Кастомный сеттер для свойства в категории
 */
- (void)setSidemenuOverlayView:(UIControl *)control
{
    objc_setAssociatedObject(self, @selector(sidemenuOverlayView), control, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
}

/*
 * Кастомный геттер для свойства в категории
 */
- (UIControl*)sidemenuOverlayView
{
    return objc_getAssociatedObject(self, @selector(sidemenuOverlayView));
}

#pragma mark - Работа с оверлеем бокового меню

- (void)showSidemenuOverlay
{
    DPLogFast(@"");
    
    if (self.sidemenuOverlayView == nil) {
        self.sidemenuOverlayView = [[UIControl alloc] initWithFrame:self.view.frame];
        [self.sidemenuOverlayView setBackgroundColor:[[UIColor blackColor] colorWithAlphaComponent:DefaultSidemenuOverlayAlpha]];
        [self.sidemenuOverlayView addTarget:self
                                     action:@selector(sidemenuOverlayTouched)
                           forControlEvents:UIControlEventTouchUpInside];
    }
    [self.view insertSubview:self.sidemenuOverlayView belowSubview:self.sidemenuContainerView];
}

- (void)hideSidemenuOverlay
{
    DPLogFast(@"");
    
    if (self.sidemenuOverlayView == nil)
        return;
    
    [self.sidemenuOverlayView removeFromSuperview];
}

#pragma mark - Работа с боковым меню

- (void)placeControllerInSidemenu:(UIViewController*)svc
{
    if ((svc == nil) || (self.sidemenuContainerView == nil))
        return;
    
    [svc.view setWidth:DefaultSidemenuWidth];
    [svc.view setHeight:DefaultSidemenuHeight];
    
    [self addChildViewController:svc];
    [self.sidemenuContainerView addSubview:svc.view];
    [svc didMoveToParentViewController:self];
}

- (void)showSidemenu:(VoidResponseBlock)completionBlock
{
    DPLogFast(@"");
    
    if (self.sidemenuContainerView == nil) {
        self.sidemenuContainerView = [[UIView alloc] initWithFrame:CGRectMake(-DefaultSidemenuWidth, 0,
                                                                              DefaultSidemenuWidth, DefaultSidemenuHeight)];
        [self.sidemenuContainerView setBackgroundColor:DefaultWhiteColor];
        [self.view addSubview:self.sidemenuContainerView];
        [self placeControllerInSidemenu:ControllerById(SidemenuViewControllerID)];
    }
    
    [self.view bringSubviewToFront:self.sidemenuContainerView];
    [self showSidemenuOverlay];
    
    [self.sidemenuOverlayView setAlpha:0.f];
    [UIView animateWithDuration:KeyboardAnimationDuration animations:^(void) {
        [self.sidemenuOverlayView setAlpha:1.f];
        [self.sidemenuContainerView setX:0];
    } completion:^(BOOL finished) {
        if (completionBlock)
            completionBlock();
    }];
}

- (void)hideSidemenu:(VoidResponseBlock)completionBlock
{
    DPLogFast(@"");
    
    if (self.sidemenuContainerView == nil)
        return;
    
    [self.sidemenuOverlayView setAlpha:1.f];
    [UIView animateWithDuration:KeyboardAnimationDuration animations:^(void) {
        [self.sidemenuOverlayView setAlpha:0.f];
        [self.sidemenuContainerView setX:-DefaultSidemenuWidth];
    } completion:^(BOOL finished) {
        [self hideSidemenuOverlay];
        if (completionBlock)
            completionBlock();
    }];
}

#pragma mark - Обработка пользовательского взаимодействия

- (void)sidemenuOverlayTouched
{
    DPLogFast(@"");
    
    [self hideSidemenu:nil];
}

@end

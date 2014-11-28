//
//  AbstractViewController+Modal.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController+Modal.h"
#import "UIView+Screenshot.h"
#import "UIImage+ImageEffects.h"
#import "CustomModalSegue.h"
#import "ModalViewController.h"
#import <objc/runtime.h>

#define LOG_ON 1

@implementation AbstractViewController (Modal)

@dynamic blurredView;

#pragma mark - Работа с задним слоем модального контроллера

/*
 * Кастомный сеттер для свойства в категории
 */
- (void)setBlurredView:(UIView*)view
{
    objc_setAssociatedObject(self, @selector(blurredView), view, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
}

/*
 * Кастомный геттер для свойства в категории
 */
- (UIView*)blurredView
{
    return objc_getAssociatedObject(self, @selector(blurredView));
}

/*
 * Создание слоя с размытым изображением экрана
 */
- (UIView*)createBlurredView
{
    UIView *blurred_view = [[UIView alloc] initWithFrame:self.view.bounds];
    UIImageView *image_view = [[UIImageView alloc] initWithFrame:self.view.bounds];
    
    // эффект blur для iOS 8
    if (NSClassFromString(@"UIVisualEffectView") != nil) {
        image_view.image = self.view.imageRepresentation;
        [blurred_view addSubview:image_view];
        
        UIVisualEffectView *visual_effect_view = [[UIVisualEffectView alloc] initWithEffect:[UIBlurEffect effectWithStyle:UIBlurEffectStyleDark]];
        visual_effect_view.frame = self.view.bounds;
        [blurred_view addSubview:visual_effect_view];
        return blurred_view;
    }
    
    // эффект blur для iOS 7
    UIColor *tintColor = [UIColor colorWithWhite:0.2 alpha:0.6];
    //blurred_image_view.image = self.view.imageRepresentation;
    image_view.image = [self.view.imageRepresentation applyBlurWithRadius:8
                                                                tintColor:tintColor
                                                    saturationDeltaFactor:1.8
                                                                maskImage:nil];
    
    [blurred_view addSubview:image_view];
    return blurred_view;
}

#pragma mark - Показ и скрытие модального контроллера

/**
 * Отображение модального контроллера
 */
- (void)showViewControllerModally:(AbstractViewController *)destinationVC
{
    DPLog(LOG_ON, @"");
    
    if (destinationVC == nil)
        return;
    
    ModalViewController *modal_vc = (ModalViewController *)ControllerById(ModalViewControllerID);
    [modal_vc placeViewController:destinationVC];
    
    UIView *blurred_view = [self createBlurredView];
    modal_vc.blurredView = blurred_view;
    [modal_vc.view insertSubview:modal_vc.blurredView atIndex:0];
    
    CATransition* transition = [CATransition animation];
    transition.duration = KeyboardAnimationDuration;
    transition.type = kCATransitionFade;
    transition.subtype = kCATransitionFromBottom;
    
    [self.view.window.layer addAnimation:transition forKey:kCATransition];
    [self.navigationController pushViewController:modal_vc animated:NO];
    [CATransaction commit];
}

/**
 * Скрытие модального контроллера
 */
- (void)hideModalViewController:(ModalViewController *)modalViewController
{
    DPLog(LOG_ON, @"");
    
    [self.view endEditing:YES];
    
    [modalViewController hideContainerView:^(BOOL finished) {
        CATransition *transition = [CATransition animation];
        transition.duration = KeyboardAnimationDuration;
        transition.type = kCATransitionFade;
        transition.subtype = kCATransitionFromBottom;
        transition.removedOnCompletion = YES;
        
        [[UIApplication sharedApplication].keyWindow.layer addAnimation:transition forKey:@"transition"];
        [[UIApplication sharedApplication] beginIgnoringInteractionEvents];
        [CATransaction setCompletionBlock:^(void) {
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(transition.duration * NSEC_PER_SEC)), dispatch_get_main_queue(), ^ {
                [[UIApplication sharedApplication] endIgnoringInteractionEvents];
            });
        }];
        
        [modalViewController.navigationController popViewControllerAnimated:NO];
        [CATransaction commit];
    }];
}

@end

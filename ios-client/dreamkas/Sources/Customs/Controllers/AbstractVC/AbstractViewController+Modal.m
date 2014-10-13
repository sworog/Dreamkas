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
 * Создание слоя с размытым изображением экрана.
 */
- (UIView*)createBlurredView
{
    UIView *blurred_view = [[UIView alloc] initWithFrame:self.view.bounds];
    
    UIImageView *blurred_image_view = [[UIImageView alloc] initWithFrame:self.view.bounds];
    
    UIColor *tintColor = [UIColor colorWithWhite:0.2 alpha:0.6];
    blurred_image_view.image = [self.view.imageRepresentation applyBlurWithRadius:8
                                                                        tintColor:tintColor
                                                            saturationDeltaFactor:1.8
                                                                        maskImage:nil];
    
    [blurred_view addSubview:blurred_image_view];
    
    return blurred_view;
}

#pragma mark - Показ и скрытие модального контроллера

/**
 * Отображение модального контроллера
 */
- (void)showViewControllerModally:(AbstractViewController *)destVC segueId:(NSString *)segueId
{
    DPLog(LOG_ON, @"");
    
    if (destVC == nil)
        return;
    
    CustomModalSegue *segue = [CustomModalSegue segueWithIdentifier:segueId source:self
                                                        destination:destVC performHandler:^(void) {}];
    [segue perform];
}

/**
 * Скрытие модального контроллера
 */
- (void)hideModalViewController
{
    DPLog(LOG_ON, @"");
    
    CATransition *transition = [CATransition animation];
    transition.duration = 0.15;
    transition.type = kCATransitionFade;
    transition.subtype = kCATransitionFromBottom;
    transition.removedOnCompletion = YES;
    
    [[UIApplication sharedApplication].keyWindow.layer addAnimation:transition forKey:@"transition"];
    [[UIApplication sharedApplication] beginIgnoringInteractionEvents];
    [CATransaction setCompletionBlock: ^ {
        dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(transition.duration * NSEC_PER_SEC)), dispatch_get_main_queue(), ^ {
            [[UIApplication sharedApplication] endIgnoringInteractionEvents];
        });
    }];
    
    [self dismissViewControllerAnimated:NO completion:nil];
    
    [CATransaction commit];
}

@end

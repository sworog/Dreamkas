//
//  AbstractViewController+Modal.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController+Modal.h"
#import "ModalViewController.h"

#define LOG_ON 1

@implementation AbstractViewController (Modal)

#pragma mark - Показ и скрытие модального контроллера

/**
 * Отображение модального контроллера
 */
- (void)showViewControllerModally:(AbstractViewController *)destinationVC
                     onCompletion:(void (^)(BOOL finished))completionBlock
{
    DPLog(LOG_ON, @"");
    
    if (destinationVC == nil)
        return;
    
    ModalViewController *modal_vc = (ModalViewController *)ControllerById(ModalViewControllerID);
    [modal_vc placeViewController:destinationVC];
    modal_vc.view.backgroundColor = [DefaultBlackColor colorWithAlphaComponent:DefaultModalOverlayAlpha];
    modal_vc.modalPresentationStyle = UIModalPresentationOverCurrentContext;
    
    CATransition* transition = [CATransition animation];
    transition.duration = KeyboardAnimationDuration;
    transition.type = kCATransitionFade;
    transition.subtype = kCATransitionFromBottom;
    transition.removedOnCompletion = YES;
    
    [[UIApplication sharedApplication].keyWindow.layer addAnimation:transition forKey:@"transition"];
    [[UIApplication sharedApplication] beginIgnoringInteractionEvents];
    [CATransaction setCompletionBlock:^(void) {
        dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(transition.duration * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
            [[UIApplication sharedApplication] endIgnoringInteractionEvents];
        });
        
        [self presentViewController:modal_vc animated:NO completion:^{
            [CATransaction commit];
            if (completionBlock)
                completionBlock(YES);
        }];
    }];
}

/**
 * Скрытие модального контроллера
 */
- (void)hideModalViewController:(ModalViewController *)modalViewController
                   onCompletion:(void (^)(BOOL finished))completionBlock
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
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(transition.duration * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
                [[UIApplication sharedApplication] endIgnoringInteractionEvents];
            });
            
            [modalViewController dismissViewControllerAnimated:NO completion:^{
                [CATransaction commit];
                if (completionBlock)
                    completionBlock(YES);
            }];
        }];
    }];
}

@end

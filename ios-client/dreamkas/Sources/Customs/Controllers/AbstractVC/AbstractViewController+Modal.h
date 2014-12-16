//
//  AbstractViewController+Modal.h
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

@class ModalViewController;

@interface AbstractViewController (Modal)

/**
 * Отображение модального контроллера
 */
- (void)showViewControllerModally:(AbstractViewController *)destinationVC
                     onCompletion:(void (^)(BOOL finished))completionBlock;

/**
 * Скрытие модального контроллера
 */
- (void)hideModalViewController:(ModalViewController *)modalViewController
                   onCompletion:(void (^)(BOOL finished))completionBlock;

@end

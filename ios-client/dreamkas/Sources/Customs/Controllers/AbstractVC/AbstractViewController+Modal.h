//
//  AbstractViewController+Modal.h
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

@interface AbstractViewController (Modal)

/** Дополнительный слой, который содержит размытое изображение экрана */
@property (nonatomic, strong) UIView *blurredView;

/** Создание слоя с размытым изображением экрана */
- (UIView*)createBlurredView;

/**
 * Отображение модального контроллера
 */
- (void)showViewControllerModally:(AbstractViewController *)destinationVC;

/**
 * Скрытие модального контроллера
 */
- (void)hideModalViewController;

@end

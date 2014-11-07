//
//  AbstractNavigationController.h
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>

/**
 Модифицированный UINavigationController, оптимизированный под работу в заданных ориентациях.
 Поддерживаемые ориентации задаются корневым контроллером данного UINavigationController.
 Рекомендуется к использованию в iOS 5.0 и выше.
 */

@interface AbstractNavigationController : UINavigationController

/** @name Данные методы необходимо определить в классе корневого контроллера */

- (BOOL)shouldAutorotate;
- (NSUInteger)supportedInterfaceOrientations;
- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation;

@end

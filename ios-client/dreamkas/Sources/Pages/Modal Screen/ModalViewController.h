//
//  ModalViewController.h
//  dreamkas
//
//  Created by sig on 27.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

@interface ModalViewController : AbstractViewController

/**
 * Установка контроллера в контейнер модального контроллера
 */
- (void)placeViewController:(UIViewController *)viewController;

@end

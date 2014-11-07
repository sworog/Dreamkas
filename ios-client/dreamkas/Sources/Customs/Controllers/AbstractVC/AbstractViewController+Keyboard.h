//
//  AbstractViewController+Keyboard.h
//  dreamkas
//
//  Created by sig on 31.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

@interface AbstractViewController (Keyboard)

/** Конфигурирование контроллера на отлов событий клавиатуры */
- (void)becomeKeyboardEventsListener;

@end

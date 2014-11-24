//
//  ActionButton.h
//  dreamkas
//
//  Created by sig on 20.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ActionButton : UIButton

/**
 * Установка текста кнопки для основного состояния и состояния подтверждения
 */
- (void)setStateNormalTitle:(NSString *)normalTitle setStateSelectedTitle:(NSString *)selectedTitle;

@end

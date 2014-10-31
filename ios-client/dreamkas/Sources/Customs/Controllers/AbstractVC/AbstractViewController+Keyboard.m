//
//  AbstractViewController+Keyboard.m
//  dreamkas
//
//  Created by sig on 31.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController+Keyboard.h"

@implementation AbstractViewController (Keyboard)

#pragma mark - Обработка событий клавиатуры

- (void)becomeKeyboardEventsListener
{
    if ([self respondsToSelector:@selector(keyboardWillAppear:)]) {
        // слушаем уведомления о показе клавиатуры
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(keyboardWillAppear:)
                                                     name:UIKeyboardWillShowNotification
                                                   object:nil];
    }
    
    if ([self respondsToSelector:@selector(keyboardWillAppear:)]) {
        // слушаем уведомления о скрытии клавиатуры
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(keyboardWillDisappear:)
                                                     name:UIKeyboardWillHideNotification
                                                   object:nil];
    }
}

- (void)keyboardWillAppear:(NSNotification *)notification
{
    DPLogFast(@"");
    
    @throw [NSException exceptionWithName:@"Вызов метода из KeyboardEventsListenerProtocol запрещен"
                                   reason:@"Данный метод запрещено вызывать у абстрактного контроллера"
                                 userInfo:notification.userInfo];
}

- (void)keyboardWillDisappear:(NSNotification *)notification
{
    @throw [NSException exceptionWithName:@"Вызов метода из KeyboardEventsListenerProtocol запрещен"
                                   reason:@"Данный метод запрещено вызывать у абстрактного контроллера"
                                 userInfo:notification.userInfo];
}

@end

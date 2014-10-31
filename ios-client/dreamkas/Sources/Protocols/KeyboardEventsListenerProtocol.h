//
//  KeyboardEventsListenerProtocol.h
//  dreamkas
//
//  Created by sig on 31.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@protocol KeyboardEventsListenerProtocol <NSObject>

/** Уведомление о показе клавиатуры */
- (void)keyboardWillAppear:(NSNotification *)notification;

/** Уведомление о скрытии клавиатуры */
- (void)keyboardWillDisappear:(NSNotification *)notification;

@end

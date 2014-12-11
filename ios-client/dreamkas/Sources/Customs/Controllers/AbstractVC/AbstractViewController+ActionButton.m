//
//  AbstractViewController+ActionButton.m
//  dreamkas
//
//  Created by sig on 25.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController+ActionButton.h"
#import <objc/runtime.h>

@implementation AbstractViewController (ActionButton)

@dynamic actionButtonsInfoArray;

#pragma mark - Работа в рантайме

/*
 * Кастомный сеттер для свойства в категории
 */
- (void)setActionButtonsInfoArray:(NSArray *)array
{
    objc_setAssociatedObject(self, @selector(actionButtonsInfoArray), array, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
}

/*
 * Кастомный геттер для свойства в категории
 */
- (UIView*)actionButtonsInfoArray
{
    return objc_getAssociatedObject(self, @selector(actionButtonsInfoArray));
}

#pragma mark - Работаем с уведомлениями

/**
 * Слушаем касания основного окна приложения
 */
- (void)becomeWindowTapEventsListener:(NSArray *)params
{
    if ([params count] < 1) {
        return;
    }
    
    [self setActionButtonsInfoArray:params];
    
    if ([self respondsToSelector:@selector(onWindowTapNotification:)]) {
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(onWindowTapNotification:)
                                                     name:WindowTapNotificationName
                                                   object:nil];
    }
}

/**
 * Обработчик касания экрана
 */
- (void)onWindowTapNotification:(NSNotification *)notification
{
    UITapGestureRecognizer *recognizer = (UITapGestureRecognizer*)notification.object;
    CGPoint touch = [recognizer locationInView:self.view];
    
    for (NSDictionary *dict in self.actionButtonsInfoArray) {
        
        // проверяем необходимость обработки касания экрана
        // т.к. если ни одна кнопка не находится в состоянии подтверждения -
        // нам не нужно выполнять никаких действий
        BOOL should_process_touch = NO;
        for (UIButton *btn in dict[@"buttons"]) {
            if ([btn isSelected]) {
                should_process_touch = YES;
                break;
            }
        }
        
        if (should_process_touch == NO) {
            return;
        }
        
        // проверяем, что касание произошло за пределами кнопки,
        // чтобы быть уверенным, что можно сбрасывать её состояние
        int i = 0;
        for (UIView *holder_view in dict[@"holders"]) {
            UIButton *btn = dict[@"buttons"][i];
            CGRect rect = [holder_view convertRect:btn.frame toView:self.view];
            
            if (CGRectContainsPoint(rect, touch) == NO) {
                [btn setSelected:NO];
            }
            i++;
        }
    }
}

@end

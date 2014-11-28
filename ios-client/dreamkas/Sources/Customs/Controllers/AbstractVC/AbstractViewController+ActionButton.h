//
//  AbstractViewController+ActionButton.h
//  dreamkas
//
//  Created by sig on 25.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

@interface AbstractViewController (ActionButton)

/** Массив со всей необходимой информацией о кнопках */
@property (nonatomic, strong) NSArray *actionButtonsInfoArray;

/**
 * Слушаем касания основного окна приложения
 */
- (void)becomeWindowTapEventsListener:(NSArray *)params;

@end

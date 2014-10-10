//
//  RootViewController.h
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface RootViewController : UIViewController

/** @name Методы для отображения/скрытия индикация при загрузке и обработке данных */

/** Отображение индикатора поверх всего экрана */
- (void)showLoading;
- (void)showLoadingWithText:(NSString*)text;
/** Скрытие всех индикаторов */
- (void)hideLoading;
- (void)hideLoadingAfterDelay:(NSTimeInterval)delay;

/** @name Базовые методы настройки контроллера */

/** Обобщенный метод инициализации контроллера */
- (void)initialize;
/** Установка локализованных строк */
- (void)configureLocalization;
/** Настройка фона экрана */
- (void)configureBackground;
/** Обработка нажатия по кнопке возврата к предыдущему экрану */
- (void)backButtonClicked;

@end

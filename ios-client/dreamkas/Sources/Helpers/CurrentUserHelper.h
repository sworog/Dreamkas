//
//  CurrentUserHelper.h
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface CurrentUserHelper : NSObject

/** Сущность синглтона */
+ (CurrentUserHelper *)instance;

/** Токен для автологина или повторной авторизации, если accessToken устарел */
@property (nonatomic, readonly) NSString *refreshToken;

/** Ссылка на последний использованный магазин */
@property (nonatomic, readonly) NSString *lastUsedStoreID;

/**
 * Метод, уведомляющий о наличии авторизационных данных с предыдущего входа
 */
- (BOOL)hasActualAuthData;

/**
 * Метод для установки новых авторизационных данныех для входа в приложение
 */
- (void)updateRefreshToken:(NSString *)refToken;

/**
 * Метод для сброса последних использованных авторизационных данных
 */
- (void)resetLastUsedAuthData;

/**
 * Метод для установки нового идентификатора магазина
 */
- (void)updateLastUsedStoreID:(NSString *)newStoreID;

@end

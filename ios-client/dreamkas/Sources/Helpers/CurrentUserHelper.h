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

/** Ссылка на последний использованный логин */
@property (nonatomic, readonly) NSString *lastUsedLogin;

/** Ссылка на последний использованный пароль */
@property (nonatomic, readonly) NSString *lastUsedPassword;

/**
 * Метод, уведомляющий о наличии авторизационных данных с предыдущего входа
 */
- (BOOL)hasActualAuthData;

/**
 * Метод для установки новых авторизационных данныех для входа в приложение
 */
- (void)updateLastUsedLogin:(NSString *)newLogin lastUsedPassword:(NSString *)newPassword;

@end

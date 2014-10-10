//
//  CurrentUserHelper.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CurrentUserHelper.h"
#import "AESCrypt.h"

#define CurrentUserPrivateStorePassword @"RaSlccufRPZBDqoBfKC8"
#define UserDefaultsKey(pname) [NSString stringWithFormat:@"%@_%@", NSStringFromClass([self class]), NSStringFromSelector(@selector(pname))]

@implementation CurrentUserHelper

@synthesize lastUsedLogin, lastUsedPassword;

#pragma mark - Инициализация

+ (CurrentUserHelper*)instance
{
    static CurrentUserHelper *_instance = nil;
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        _instance = [[CurrentUserHelper alloc] init];
    });
    
    return _instance;
}

- (id)init
{
    self = [super init];
    if (self) {
        lastUsedLogin = [UserDefaults valueForKey:UserDefaultsKey(lastUsedLogin)];
        lastUsedPassword = [AESCrypt decrypt:[UserDefaults valueForKey:UserDefaultsKey(lastUsedPassword)]
                                    password:CurrentUserPrivateStorePassword];
        DPLogFast(@"lastUsedLogin = %@, lastUsedPassword = %@", lastUsedLogin, lastUsedPassword);
    }
    
    return self;
}

#pragma mark - Логика

/**
 * Метод, уведомляющий о наличии авторизационных данных с предыдущего входа
 */
- (BOOL)hasActualAuthData
{
    return ([lastUsedLogin length] && [lastUsedPassword length]);
}

/**
 * Метод для установки новых авторизационных данныех для входа в приложение
 */
- (void)updateLastUsedLogin:(NSString *)newLogin lastUsedPassword:(NSString *)newPassword
{
    DPLogFast(@"");
    
    if ([newLogin length] < 1 || [newPassword length] < 1)
        return;
    
    lastUsedLogin = newLogin.copy;
    lastUsedPassword = newPassword.copy;
    
    [UserDefaults setValue:lastUsedLogin forKey:UserDefaultsKey(lastUsedLogin)];
    [UserDefaults setValue:[AESCrypt encrypt:lastUsedPassword password:CurrentUserPrivateStorePassword]
                    forKey:UserDefaultsKey(lastUsedPassword)];
    [UserDefaults synchronize];
}

@end

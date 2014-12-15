//
//  CurrentUserHelper.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CurrentUserHelper.h"
#import "AESCrypt.h"

#define RefreshTokenPrivateStorePassword @"RaSlccufRPZBDqoBfKC8"
#define UserDefaultsKey(pname) [NSString stringWithFormat:@"%@_%@", NSStringFromClass([self class]), NSStringFromSelector(@selector(pname))]

@implementation CurrentUserHelper

@synthesize refreshToken, lastUsedStoreID;

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
        refreshToken = [AESCrypt decrypt:[UserDefaults valueForKey:UserDefaultsKey(refreshToken)]
                                password:RefreshTokenPrivateStorePassword];
        lastUsedStoreID = [UserDefaults valueForKey:UserDefaultsKey(lastUsedStoreID)];

        DPLogFast(@"refreshToken = %@", refreshToken);
        DPLogFast(@"lastUsedStoreID = %@", lastUsedStoreID);
    }
    
    return self;
}

#pragma mark - Логика

/**
 * Метод, уведомляющий о наличии авторизационных данных с предыдущего входа
 */
- (BOOL)hasActualAuthData
{
    return [refreshToken length];
}

/**
 * Метод для установки новых авторизационных данныех для входа в приложение
 */
- (void)updateRefreshToken:(NSString *)refToken
{
    DPLogFast(@"");
    
    if ([refToken length] < 1)
        return;
    
    refreshToken = refToken.copy;
    
    [UserDefaults setValue:[AESCrypt encrypt:refreshToken password:RefreshTokenPrivateStorePassword]
                    forKey:UserDefaultsKey(refreshToken)];
    [UserDefaults synchronize];
    
    DPLogFast(@"refreshToken = %@", [UserDefaults valueForKey:UserDefaultsKey(refreshToken)]);
}

/**
 * Метод для сброса последних использованных авторизационных данных
 */
- (void)resetLastUsedAuthData
{
    refreshToken = nil;
    
    [UserDefaults setValue:nil forKey:UserDefaultsKey(refreshToken)];
    [UserDefaults synchronize];
}

/**
 * Метод для установки нового идентификатора магазина
 */
- (void)updateLastUsedStoreID:(NSString *)newStoreID
{
    DPLogFast(@"");
    
    if ([newStoreID length] < 1)
        return;
    
    lastUsedStoreID = newStoreID.copy;
    
    [UserDefaults setValue:lastUsedStoreID forKey:UserDefaultsKey(lastUsedStoreID)];
    [UserDefaults synchronize];
    
    // рассылаем уведомление о смене магазина
    NSNotification *notification = [NSNotification notificationWithName:StoreChangedNotificationName
                                                                 object:nil userInfo:nil];
    [[NSNotificationCenter defaultCenter] postNotification:notification];
}

@end

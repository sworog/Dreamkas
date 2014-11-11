//
//  DeviceInfoHelper.h
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface DeviceInfoHelper : NSObject

/** Определение версии iOS */
+ (float)iOSVersion;

/** Модель устройства в явном виде */
+ (NSString*)deviceModel;

/** Метод для проверки соединения с сетью Интернет */
+ (BOOL)networkIsReachable;

/** Метод для выдачи идентификатора устройства */
+ (NSString *)uniqueDeviceIdentifier;

@end

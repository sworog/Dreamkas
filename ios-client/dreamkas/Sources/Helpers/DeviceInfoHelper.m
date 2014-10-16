//
//  DeviceInfoHelper.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "DeviceInfoHelper.h"
#import <CommonCrypto/CommonDigest.h>
#import <sys/utsname.h>

#define SimulatorTitle @"Simulator iOS"

@implementation DeviceInfoHelper

/** 
 * Определение версии iOS 
 */
+ (float)iOSVersion
{
    NSString *ver = [[UIDevice currentDevice] systemVersion];
    return [ver floatValue];
}

/** 
 * Модель устройства в явном виде 
 */
+ (NSString*)deviceModel
{
    struct utsname systemInfo;
    uname(&systemInfo);
    NSString *str = @(systemInfo.machine);
    
    if ([str isEqualToString:@"i386"] || [str isEqualToString:@"x86_64"])
        str = SimulatorTitle;
    
    return str;
}

/**
 * Метод для проверки соединения с сетью Интернет
 */
+ (BOOL)networkIsReachable
{
    Reachability *reachability = [Reachability reachabilityWithHostName:@"google.com"];
    NetworkStatus remote_host_status = [reachability currentReachabilityStatus];
    BOOL rv = NO;
    
    if(remote_host_status == NotReachable) {
        // nothing to do here..
    }
    else if (remote_host_status == ReachableViaWWAN) {
        rv = YES;
    }
    else if (remote_host_status == ReachableViaWiFi) {
        rv = YES;
    }
    
    DPLogFast(@"[I] Internet Status: %@", (rv)?@"Connected":@"Not Connected");
    return rv;
}

/**
 *  Метод для выдачи идентификатора устройства
 */
+ (NSString *)uniqueDeviceIdentifier
{
    NSString *device_id = nil;
    
    if ([[self deviceModel] isEqualToString:SimulatorTitle]) {
        // static id for simulator
        device_id = @"FE7145CF-0077-452B-871F-C8DE253486D7";
    }
    else if (CurrentIOSVersion >= 6.f) {
        // iOS 6 and later
        device_id = [[[UIDevice currentDevice] identifierForVendor] UUIDString];
    }
    return device_id;
}

@end

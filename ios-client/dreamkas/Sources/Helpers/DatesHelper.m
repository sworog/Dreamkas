//
//  DatesHelper.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "DatesHelper.h"

@implementation DatesHelper

/**
 * Форматтер даты по стандарту ISO-8601
 */
+ (NSDateFormatter *)defaultDateFormatter
{
    NSDateFormatter *df = [NSDateFormatter new];
    [df setLocale:[NSLocale currentLocale]];
    [df setTimeZone:[NSTimeZone localTimeZone]];
    [df setDateFormat:@"yyyy-MM-dd'T'HH:mm:ssZZZZ"];
    return df;
}

@end

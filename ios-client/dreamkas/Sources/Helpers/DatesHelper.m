//
//  DatesHelper.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "DatesHelper.h"

@implementation DatesHelper

+ (NSDateFormatter *)defaultDateFormatter
{
    NSDateFormatter *df = [NSDateFormatter new];
    df.locale = [NSLocale currentLocale];
    [df setDateFormat:@"yyyy-MM-dd HH:mm"];
    [df setTimeZone:[NSTimeZone localTimeZone]];
    return df;
}

@end

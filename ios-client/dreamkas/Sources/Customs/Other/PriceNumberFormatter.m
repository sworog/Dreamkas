//
//  PriceNumberFormatter.m
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PriceNumberFormatter.h"

@implementation PriceNumberFormatter

- (id)init
{
    self = [super init];
    if (self) {
        [self initialize];
    }
    return self;
}

- (void)initialize
{
    //
    // формат вывода: 12 000 357,85 (без символа валюты)
    //
    
    [self setNumberStyle:NSNumberFormatterDecimalStyle];
    [self setGroupingSeparator:@" "];
    [self setGroupingSize:3];
    [self setDecimalSeparator:@","];
    [self setAlwaysShowsDecimalSeparator:YES];
    [self setUsesGroupingSeparator:YES];
    [self setRoundingMode:NSNumberFormatterRoundHalfUp];
    [self setMaximumFractionDigits:DigitsAfterDotInPrices];
    [self setMinimumFractionDigits:DigitsAfterDotInPrices];
}

@end

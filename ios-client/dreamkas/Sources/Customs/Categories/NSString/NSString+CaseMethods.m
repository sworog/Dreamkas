//
//  NSString+CaseMethods.m
//  dreamkas
//
//  Created by sig on 06.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "NSString+CaseMethods.h"

@implementation NSString (CaseMethods)

- (NSString *)lowercaseStringWithFirstUppercaseLetter
{
    if ([self length] < 1) {
        return self;
    }
    
    NSMutableString *str = [[NSMutableString alloc] initWithString:[self lowercaseString]];
    NSRange range = NSMakeRange(0, 1);
    [str replaceCharactersInRange:range
                       withString:[[str substringWithRange:range] uppercaseString]];
    
    return [str copy];
}

@end

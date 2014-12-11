//
//  ValidationHelper.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ValidationHelper.h"

@implementation ValidationHelper

+ (BOOL)isPhoneValid:(NSString*)phone
{
    return [self isPhoneValid:phone flatten:NO] && phone.length >= 10;
}

+ (BOOL)isPhoneValid:(NSString*)phone flatten:(BOOL)flatten
{
    if (phone == nil || phone.length < 1)
        return NO;
    
    if (flatten)
        phone = [self flattenPhoneNumber:phone];
    
    NSError *myError;
    NSRegularExpression *telRegex = [NSRegularExpression regularExpressionWithPattern:@"^(\\+)?(?:[0-9]?){6,14}[0-9]$"
                                                                              options:NSRegularExpressionCaseInsensitive
                                                                                error:&myError];
    NSUInteger number_of_matches = [telRegex numberOfMatchesInString:phone
                                                             options:0
                                                               range:NSMakeRange(0, [phone length])];
    if (myError)
        NSLog(@"phone regex error: %@", [myError description]);
    
    return (number_of_matches > 0);
}

+ (NSString *)flattenPhoneNumber:(NSString *)number
{
    number = [number stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];;
    NSMutableString *newString = [[NSMutableString alloc] initWithString: number];
    
    if (number.length < 1)
        return @"";
    
    [newString replaceOccurrencesOfString:@"("
                               withString:@""
                                  options:NSCaseInsensitiveSearch
                                    range:NSMakeRange(0, [newString length])];
    [newString replaceOccurrencesOfString:@"-"
                               withString:@""
                                  options:NSCaseInsensitiveSearch
                                    range:NSMakeRange(0, [newString length])];
    [newString replaceOccurrencesOfString:@")"
                               withString:@""
                                  options:NSCaseInsensitiveSearch
                                    range:NSMakeRange(0, [newString length])];
    [newString replaceOccurrencesOfString:@" "
                               withString:@""
                                  options:NSCaseInsensitiveSearch
                                    range:NSMakeRange(0, [newString length])];
    
    return newString;
}

+ (BOOL)isEmailValid:(NSString*)email
{
    if (email == nil || [email isEqualToString:@""])
        return NO;
    
    NSError *myError = nil;
    NSRegularExpression *emailRegex = [NSRegularExpression regularExpressionWithPattern:@"^([0-9a-zA-Z]([-.\\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\\w]*[0-9a-zA-Z]\\.)+[a-zA-Z]{1,9})$"
                                                                                options:NSRegularExpressionCaseInsensitive
                                                                                  error:&myError];
    NSUInteger number_of_matches = [emailRegex numberOfMatchesInString:email
                                                               options:0
                                                                 range:NSMakeRange(0, [email length]-1)];
    if (myError)
        NSLog(@"email regex error: %@", [myError description]);
    
    //NSLog(@"numberOfMatchesEmails = %d", number_of_matches);
    return (number_of_matches > 0);
}

+ (BOOL)isPriceValid:(NSString*)price
{
    //
    // PRICE FORMAT: 99000000,99 or 6.35 or .075
    //
    
    if ([price length] < 1)
        return NO;
    
    // проверка на разрешенный словарь символов
    NSCharacterSet *allowed_charactes = [NSCharacterSet characterSetWithCharactersInString:@"0123456789.,"];
    NSCharacterSet *not_allowed_charactes = [allowed_charactes invertedSet];
    if ([price rangeOfCharacterFromSet:not_allowed_charactes].location != NSNotFound) {
        return NO;
    }
    
    // проверка на дублирование разделителей дробной части
    NSMutableString *tmp = [NSMutableString stringWithString:price];
    int matches = 0;
    matches = [tmp replaceOccurrencesOfString:@"."
                                   withString:@"!"
                                      options:NSLiteralSearch
                                        range:NSMakeRange(0, [tmp length])];
    
    matches += [tmp replaceOccurrencesOfString:@","
                                    withString:@"!"
                                       options:NSLiteralSearch
                                         range:NSMakeRange(0, [tmp length])];
    if (matches > 1) {
        return NO;
    }
    
    // проверка, что целая часть не длиннее 8 цифр, а дробная - не длиннее 2
    NSArray *components = nil;
    if ([price rangeOfString:@"."].location != NSNotFound) {
        components = [price componentsSeparatedByString:@"."];
    }
    else {
        components = [price componentsSeparatedByString:@","];
    }
    if ([components count] == 1) {
        if ([price hasPrefix:@"."] || [price hasPrefix:@","]) {
            // если имеется только дробная часть
            NSString *str = [components firstObject];
            if ([str length] > DigitsAfterDotInPrices) {
                return NO;
            }
        }
        else {
            // если имеется только целая часть
            NSString *str = [components firstObject];
            if ([str length] > DigitsBeforeDotInPrices) {
                return NO;
            }
        }
    }
    else {
        NSString *first_part = [components firstObject];
        NSString *last_part = [components lastObject];
        if ([first_part length] > DigitsBeforeDotInPrices) {
            return NO;
        }
        if ([last_part length] > DigitsAfterDotInPrices) {
            return NO;
        }
    }
    
    return YES;
}

@end

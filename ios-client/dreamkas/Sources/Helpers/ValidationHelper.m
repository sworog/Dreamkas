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
    // PRICE FORMAT: 99000000,00 or 6.35 or .075
    //
    
    if ([price length] < 1)
        return NO;
    
    NSError *myError = nil;
    NSString *regex = [NSString stringWithFormat:@"^(\\d{1,%d}([.]\\d{1,%d})?|[.]\\d{1,%d})$", DigitsBeforeDotInPrices, DigitsAfterDotInPrices, DigitsAfterDotInPrices];
    NSLog(@"regex = %@", regex);
    NSLog(@"price = %@<=", price);
    
    NSRegularExpression *priceRegex = [NSRegularExpression regularExpressionWithPattern:@"\\d*[.]?\\d+"
                                                                                options:NSRegularExpressionCaseInsensitive
                                                                                  error:&myError];
    NSUInteger number_of_matches = [priceRegex numberOfMatchesInString:price
                                                               options:0
                                                                 range:NSMakeRange(0, [price length])];
    if (myError)
        NSLog(@"price regex error: %@", [myError description]);
    
    NSLog(@"number of matches for price = %d", number_of_matches);
    return (number_of_matches > 0);
}

@end

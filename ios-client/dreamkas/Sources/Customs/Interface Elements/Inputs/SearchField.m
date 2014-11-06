//
//  SearchField.m
//  dreamkas
//
//  Created by sig on 30.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SearchField.h"

#define LeftSpace 0

@implementation SearchField

- (id)initWithCoder:(NSCoder *)aDecoder
{
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self initialize];
    }
    return self;
}

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        [self initialize];
    }
    return self;
}

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
    self.contentVerticalAlignment = UIControlContentVerticalAlignmentCenter;
    
    [self setBackgroundColor:[UIColor clearColor]];
    [self setTextAlignment:NSTextAlignmentLeft];
    
    [self setClearButtonMode:UITextFieldViewModeWhileEditing];
    [self setReturnKeyType:UIReturnKeyDone];
    
    self.textColor = DefaultDarkGrayColor;
    self.font = DefaultFont(18);
    self.autocorrectionType = UITextAutocorrectionTypeNo;
    
    self.clearButtonMode = UITextFieldViewModeWhileEditing;
    
    [self setLeftContentInset:LeftSpace];
}

#pragma mark - Дополнительные публичные методы

/**
 * Установка левого отступа
 */
- (void)setLeftContentInset:(CGFloat)contentInset
{
    self.leftView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, contentInset, self.frame.size.height)];
    self.leftViewMode = UITextFieldViewModeAlways;
}

/**
 * Установка правого отступа
 */
- (void)setRightContentInset:(CGFloat)contentInset
{
    self.rightView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, contentInset, self.frame.size.height)];
    self.rightViewMode = UITextFieldViewModeAlways;
}

#pragma mark - Настройка Placeholder

- (void)drawPlaceholderInRect:(CGRect)rect
{
    if (CurrentIOSVersion >= 7.0)
        rect.origin.y = 20;
    
    NSDictionary *dictionary = @{ NSFontAttributeName: self.font,
                                  NSForegroundColorAttributeName: DefaultGrayColor};
    [[self placeholder] drawInRect:rect withAttributes:dictionary];
}

- (CGRect)textRectForBounds:(CGRect)bounds
{
    return CGRectInset(bounds, LeftSpace, 0);
}

- (CGRect)editingRectForBounds:(CGRect)bounds
{
    return CGRectInset(bounds, LeftSpace, 0);
}

- (CGRect)placeholderRectForBounds:(CGRect)bounds
{
    return CGRectInset(bounds, LeftSpace, 0);
}

@end

//
//  CustomTextField.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "OldCustomTextField.h"

#define LeftSpace 0

@implementation OldCustomTextField

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
    self.height = DefaultTextFieldHeight;
    self.contentVerticalAlignment = UIControlContentVerticalAlignmentCenter;
    
    self.backgroundColor = [UIColor clearColor];
    self.background = [UIImage imageNamed:@"TextfieldBgImg"];
    
    self.textColor = DefaultBlackColor;
    self.font = DefaultLightFont(17);
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
        rect.origin.y = 12;
    
    NSDictionary *dictionary = @{ NSFontAttributeName: self.font,
                                  NSForegroundColorAttributeName: DefaultLightGrayColor};
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

//
//  ActionButton.m
//  dreamkas
//
//  Created by sig on 20.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ActionButton.h"

@implementation ActionButton

- (instancetype)init
{
    self = [super init];
    if (self) {
        [self initialize];
    }
    return self;
}

- (instancetype)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        [self initialize];
    }
    return self;
}

- (instancetype)initWithCoder:(NSCoder *)aDecoder
{
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self initialize];
    }
    return self;
}

- (void)initialize
{
    self.height = DefaultButtonHeight;
    [[self layer] setBackgroundColor:DefaultWhiteColor.CGColor];
    
    self.titleLabel.font = DefaultFont(16);
    [self setTitleColor:DefaultDarkGrayColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultGrayColor forState:UIControlStateHighlighted];
    [self setTitleColor:DefaultRedColor forState:UIControlStateSelected];
    [self setTitleColor:DefaultGrayColor forState:UIControlStateHighlighted | UIControlStateSelected];
}

- (void)setTitle:(NSString *)title forState:(UIControlState)state
{
    [super setTitle:[title uppercaseString] forState:state];
}

#pragma mark - Self-defined Methods

/**
 * Установка текста кнопки для основного состояния и состояния подтверждения
 */
- (void)setStateNormalTitle:(NSString *)normalTitle setStateSelectedTitle:(NSString *)selectedTitle
{
    [self setTitle:normalTitle forState:UIControlStateNormal];
    [self setTitle:normalTitle forState:UIControlStateHighlighted];
    [self setTitle:selectedTitle forState:UIControlStateSelected];
    [self setTitle:selectedTitle forState:UIControlStateHighlighted | UIControlStateSelected];
}

@end

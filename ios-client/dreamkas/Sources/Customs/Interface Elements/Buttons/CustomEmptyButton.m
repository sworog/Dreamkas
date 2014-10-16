//
//  CustomEmptyButton.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomEmptyButton.h"

@implementation CustomEmptyButton

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
    [[self layer] setCornerRadius:DefaultCornerRadius];
    [[self layer] setMasksToBounds:NO];
    
    [[self layer] setShadowColor:[[UIColor grayColor] CGColor]];
    [[self layer] setShadowRadius:DefaultBtnShadowRadius];
    [[self layer] setShadowOpacity:DefaultBtnShadowOpacity];
    [[self layer] setShadowOffset:CGSizeMake(0.f, 0.f)];
    
    self.titleLabel.font = DefaultFont(16);
    [self setTitleColor:DefaultCyanColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultLightCyanColor forState:UIControlStateHighlighted];
    [self setTitleColor:DefaultGrayColor forState:UIControlStateDisabled];
}

- (void)setTitle:(NSString *)title forState:(UIControlState)state
{
    [super setTitle:[title uppercaseString] forState:state];
}

- (void)setEnabled:(BOOL)enabled
{
    [super setEnabled:enabled];
    
    [[self layer] setBackgroundColor:(enabled)?DefaultWhiteColor.CGColor:DefaultLightGrayColor.CGColor];
}

@end

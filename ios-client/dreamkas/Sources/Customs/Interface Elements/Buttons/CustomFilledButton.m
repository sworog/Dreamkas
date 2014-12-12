//
//  CustomFilledButton.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomFilledButton.h"

@implementation CustomFilledButton

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
    [[self layer] setBackgroundColor:DefaultCyanColor.CGColor];
    [[self layer] setCornerRadius:DefaultCornerRadius];
    [[self layer] setMasksToBounds:NO];
    
//    [[self layer] setShadowColor:[[UIColor grayColor] CGColor]];
//    [[self layer] setShadowRadius:DefaultBtnShadowRadius];
//    [[self layer] setShadowOpacity:DefaultBtnShadowOpacity];
//    [[self layer] setShadowOffset:CGSizeMake(0.f, 0.f)];
    
    self.titleLabel.font = DefaultMediumFont(14);
    [self setTitleColor:DefaultWhiteColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultWhiteColor forState:UIControlStateHighlighted];
    [self setTitleColor:DefaultDarkGrayColor forState:UIControlStateDisabled];
}

- (void)setTitle:(NSString *)title forState:(UIControlState)state
{
    [super setTitle:[title uppercaseString] forState:state];
}

- (void)setHighlighted:(BOOL)highlighted
{
    [[self layer] setBackgroundColor:(highlighted)?DefaultLightCyanColor.CGColor:DefaultCyanColor.CGColor];
}

- (void)setEnabled:(BOOL)enabled
{
    [super setEnabled:enabled];
    
    [[self layer] setBackgroundColor:(enabled)?DefaultCyanColor.CGColor:DefaultPreLightGrayColor.CGColor];
}

@end

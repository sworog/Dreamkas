//
//  PanelButton.m
//  dreamkas
//
//  Created by sig on 10/11/14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PanelButton.h"

#define LineViewTag     10
#define LineViewHeight  4.f

@interface PanelButton ()

@property (nonatomic) UIView *lineView;

@end

@implementation PanelButton

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
    [[self layer] setBackgroundColor:DefaultWhiteColor.CGColor];
    
    self.titleLabel.font = DefaultFont(16);
    [self setTitleColor:DefaultGrayColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultCyanColor forState:UIControlStateHighlighted];
    [self setTitleColor:DefaultCyanColor forState:UIControlStateSelected];
    
    self.lineView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, CGRectGetWidth(self.frame), LineViewHeight)];
    [self.lineView setBackgroundColor:DefaultCyanColor];
    [self.lineView setTag:LineViewTag];
    [self.lineView setHidden:YES];
    [self addSubview:self.lineView];
}

- (void)setTitle:(NSString *)title forState:(UIControlState)state
{
    [super setTitle:[title uppercaseString] forState:state];
}

- (void)setSelected:(BOOL)selected
{
    [super setSelected:selected];
    
    [self.lineView setHidden:!selected];
}

- (void)setHighlighted:(BOOL)highlighted
{
    [super setHighlighted:highlighted];
    
    [self.lineView setHidden:!highlighted];
}

@end

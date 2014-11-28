//
//  CloseButton.m
//  dreamkas
//
//  Created by sig on 28.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CloseButton.h"

@implementation CloseButton

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
    self.backgroundColor = [UIColor clearColor];
    [self setTitleColor:DefaultDarkGrayColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultGrayColor forState:UIControlStateHighlighted];
    
    unichar ch = 0xf00d;
    self.titleLabel.font = DefaultAwesomeFont(20);
    [self setTitle:[NSString stringWithFormat:@"%C", ch] forState:UIControlStateNormal];
}

@end

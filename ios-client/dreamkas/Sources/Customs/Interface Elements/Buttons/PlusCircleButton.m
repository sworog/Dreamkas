//
//  PlusCircleButton.m
//  dreamkas
//
//  Created by sig on 10/11/14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PlusCircleButton.h"

@implementation PlusCircleButton

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
    [self setTitleColor:DefaultSuperLightCyanColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultGrayColor forState:UIControlStateHighlighted];
    
    unichar ch = 0xf055;
    self.titleLabel.font = DefaultAwesomeFont(20);
    [self setTitle:[NSString stringWithFormat:@"%C", ch] forState:UIControlStateNormal];
}

@end

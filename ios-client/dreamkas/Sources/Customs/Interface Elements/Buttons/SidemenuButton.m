//
//  SidemenuButton.m
//  dreamkas
//
//  Created by sig on 07.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SidemenuButton.h"

@implementation SidemenuButton

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
    self.titleLabel.font = DefaultLiHeiProFont(26);
    [self setTitleColor:DefaultSuperLightCyanColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultGrayColor forState:UIControlStateHighlighted];
    [self setTitle:@"≣" forState:UIControlStateNormal];
}

@end

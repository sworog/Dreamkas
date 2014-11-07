//
//  MoreButton.m
//  dreamkas
//
//  Created by sig on 07.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "MoreButton.h"

@implementation MoreButton

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
    self.titleLabel.font = DefaultBoldFont(24);
    [self setTitleColor:DefaultSuperLightCyanColor forState:UIControlStateNormal];
    [self setTitleColor:DefaultWhiteColor forState:UIControlStateHighlighted];
    [self setTitle:@"⋮" forState:UIControlStateNormal];
}

@end

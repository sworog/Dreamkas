//
//  WordWrappingLabel.m
//  dreamkas
//
//  Created by sig on 17.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "WordWrappingLabel.h"

@implementation WordWrappingLabel

- (id)init
{
    self = [super init];
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

- (id)initWithCoder:(NSCoder *)aDecoder
{
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self initialize];
    }
    return self;
}

- (void)initialize
{
    self.numberOfLines = 0;
    self.lineBreakMode = NSLineBreakByWordWrapping;
    
    self.font = DefaultFont(self.font.pointSize);
    self.backgroundColor = [UIColor clearColor];
}

- (void)setText:(NSString *)text
{
    [super setText:text];
    
    CGRect lbl_rect = self.frame;
    CGSize constraint_size = (CGSize){self.frame.size.width, CGFLOAT_MAX};
    
    if (CurrentIOSVersion >= 7.f) {
        NSAttributedString *attr_text = [[NSAttributedString alloc] initWithString:self.text attributes:@{NSFontAttributeName:super.font}];
        CGRect attr_rect = [attr_text boundingRectWithSize:constraint_size options:NSStringDrawingUsesLineFragmentOrigin context:nil];
        lbl_rect.size.height = ceilf(attr_rect.size.height);
    }
#if (__IPHONE_OS_VERSION_MIN_REQUIRED < 7)
    else {
        CGSize lbl_size = [self.text sizeWithFont:super.font constrainedToSize:constraint_size lineBreakMode:NSLineBreakByWordWrapping];
        lbl_rect.size.height = lbl_size.height;
    }
#endif
    
    self.frame = lbl_rect;
}

@end

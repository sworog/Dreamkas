//
//  AbstractViewController+Sidemenu.h
//  dreamkas
//
//  Created by sig on 21.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AbstractViewController.h"

@interface AbstractViewController (Sidemenu)

@property (nonatomic) UIView *sidemenuContainerView;
@property (nonatomic) UIControl *sidemenuOverlayView;

- (void)showSidemenu:(VoidResponseBlock)completionBlock;
- (void)hideSidemenu:(VoidResponseBlock)completionBlock;

- (BOOL)doesSidemenuShown;

@end

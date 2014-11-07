//
//  CustomModalSegue.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomModalSegue.h"

@implementation CustomModalSegue

- (void)perform
{
    AbstractViewController *source_vc = (AbstractViewController *)self.sourceViewController;
    AbstractViewController *destination_vc = (AbstractViewController *)self.destinationViewController;
    
    UIView *blurred_view = [source_vc createBlurredView];
    destination_vc.blurredView = blurred_view;
    [destination_vc.view insertSubview:destination_vc.blurredView atIndex:0];
    
    CATransition* transition = [CATransition animation];
    transition.duration = 0.05;
    transition.type = kCATransitionFade;
    transition.subtype = kCATransitionFromBottom;
    [source_vc.view.window.layer addAnimation:transition forKey:kCATransition];
    
    [source_vc.navigationController pushViewController:destination_vc animated:NO];
    //[source_vc presentViewController:destination_vc animated:NO completion:nil];
}

@end

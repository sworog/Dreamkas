//
//  ProductsViewController.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ProductsViewController.h"

@interface ProductsViewController ()

@end

@implementation ProductsViewController

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)backButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self.navigationController popToRootViewControllerAnimated:YES];
}

@end

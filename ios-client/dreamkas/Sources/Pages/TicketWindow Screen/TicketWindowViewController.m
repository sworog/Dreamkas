//
//  TicketWindowViewController.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "TicketWindowViewController.h"

@interface TicketWindowViewController ()

@end

@implementation TicketWindowViewController

#pragma mark - View Lifecycle

- (void)configureLocalization
{
    // ..
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    DPLogFast(@"");
    
    // TODO: показываем окно выбора магазина, если магазин не был выбран ранее
    // если магазин уже выбран - сразу отправляем запрос на сервер
    
    [self showViewControllerModally:ControllerById(SelectStoreViewControllerID)
                            segueId:TicketWindowToSelectStoreSegueName];
}

@end

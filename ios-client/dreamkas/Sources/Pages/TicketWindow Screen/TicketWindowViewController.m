//
//  TicketWindowViewController.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "TicketWindowViewController.h"
#import "SidemenuButton.h"

@interface TicketWindowViewController ()

@property (nonatomic, weak) IBOutlet SidemenuButton *sidemenuButton;

@property (nonatomic, weak) IBOutlet UIView *leftSideContainerView;
@property (nonatomic, weak) IBOutlet UIView *rightSideContainerView;

@end

@implementation TicketWindowViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    [self placeLeftAndRightSideControllers];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    // ..
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    if ([[CurrentUser lastUsedStoreID] length] < 1) {
        [self showViewControllerModally:ControllerById(SelectStoreViewControllerID)];
    }
}

#pragma mark - Configuration Methods

- (void)placeLeftAndRightSideControllers
{
    UINavigationController *ls_nc = ControllerById(LeftSideNavigationControllerID);
    [ls_nc.view setWidth:DefaultLeftSideWidth];
    [ls_nc.view setHeight:DefaultSideHeight];
    [self addChildViewController:ls_nc];
    [self.leftSideContainerView addSubview:ls_nc.view];
    [ls_nc didMoveToParentViewController:self];
    
    UINavigationController *rs_nc = ControllerById(RightSideNavigationControllerID);
    [rs_nc.view setWidth:DefaultRightSideWidth];
    [rs_nc.view setHeight:DefaultSideHeight];
    [self addChildViewController:rs_nc];
    [self.rightSideContainerView addSubview:rs_nc.view];
    [rs_nc didMoveToParentViewController:self];
}

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    [self.sidemenuButton setAccessibilityLabel:AI_TicketWindowPage_SidemenuButton];
    
    [self.leftSideContainerView setAccessibilityLabel:AI_TicketWindowPage_LeftContainer];
    [self.rightSideContainerView setAccessibilityLabel:AI_TicketWindowPage_RightContainer];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)sidemenuButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [self showSidemenu:^{
        // ..
    }];
}

@end

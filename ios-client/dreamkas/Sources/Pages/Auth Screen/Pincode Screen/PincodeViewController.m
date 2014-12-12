//
//  PincodeViewController.m
//  dreamkas
//
//  Created by sig on 06.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "PincodeViewController.h"

@interface PincodeViewController ()

@property (nonatomic, weak) IBOutlet RaisedFilledButton *goAheadButton;

@end

@implementation PincodeViewController

#pragma mark - Инициализация

- (void)initialize
{
    // ..
}

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // ..
}

#pragma mark - Configuration Methods

- (void)configureLocalization
{
    // ..
}

- (void)configureAccessibilityLabels
{
    [self.goAheadButton setAccessibilityLabel:AI_PincodePage_GoAheadButton];
}

@end

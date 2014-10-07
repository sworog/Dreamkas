//
//  ViewController.m
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ViewController.h"
#import "AFHTTPRequestOperationManager.h"
#import "DPLog.h"

@interface ViewController ()
{
    AFHTTPRequestOperationManager *manager;
    NSString *accessToken;
    NSString *accessTokenType;
}
@property (nonatomic, weak) IBOutlet UITextField *loginField, *pwdField;

@end

@implementation ViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    manager = [AFHTTPRequestOperationManager manager];
    [manager.requestSerializer setValue:@"application/x-www-form-urlencoded"
                     forHTTPHeaderField:@"Content-Type"];
    
    // test auth data
    [self.loginField setText:@"owner@lighthouse.pro"];
    [self.pwdField setText:@"lighthouse"];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
}

#pragma mark - Обработка пользовательского взаимодействия

- (IBAction)viewTouched:(id)sender
{
    [self.view endEditing:YES];
}

- (IBAction)authButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [NetworkManager authWithLogin:@"owner@lighthouse.pro"
                         password:@"lighthouse"
                     onCompletion:^(NSDictionary *data, NSError *error) {
                         // ..
                     }];
}

- (IBAction)groupsButtonClicked:(id)sender
{
    DPLogFast(@"");
    
    [NetworkManager requestGroups:^(NSArray *data, NSError *error) {
        //..
    }];
}

@end

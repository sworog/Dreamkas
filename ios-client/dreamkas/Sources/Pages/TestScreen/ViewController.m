//
//  ViewController.m
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ViewController.h"

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
    
    [manager POST:@"http://ios.staging.api.lighthouse.pro/oauth/v2/token"
       parameters:@{@"grant_type" : @"password",
                    @"username" : self.loginField.text,
                    @"password" : self.pwdField.text,
                    @"client_id" : @"webfront_webfront",
                    @"client_secret" : @"secret"}
          success:^(AFHTTPRequestOperation *operation, id responseObject)
     {
         DPLogFast(@"JSON: %@", responseObject);
         
         if (responseObject[@"access_token"] != nil) {
             accessToken = responseObject[@"access_token"];
             accessTokenType = responseObject[@"token_type"];
         }
         
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         DPLogFast(@"Error: %@", error);
         DPLogFast(@"Data = %@", [[NSString alloc] initWithData:operation.responseData
                                                       encoding:NSASCIIStringEncoding]);
     }];
}

- (IBAction)groupsButtonClicked:(id)sender
{
    DPLogFast(@"accessToken = %@", accessToken);
    DPLogFast(@"accessTokenType = %@", [accessTokenType capitalizedString]);
    
    [manager.requestSerializer setValue:nil
                     forHTTPHeaderField:@"Content-Type"];
    [manager.requestSerializer setValue:[NSString stringWithFormat:@"%@ %@", [accessTokenType capitalizedString], accessToken]
                     forHTTPHeaderField:@"Authorization"];
    
    [manager GET:@"http://ios.staging.api.lighthouse.pro/api/1/catalog/groups.json"
      parameters:nil
         success:^(AFHTTPRequestOperation *operation, id responseObject)
     {
         DPLogFast(@"JSON: %@", responseObject);
         
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         DPLogFast(@"Error: %@", error);
         DPLogFast(@"Data = %@", [[NSString alloc] initWithData:operation.responseData
                                                       encoding:NSASCIIStringEncoding]);
     }];
}

@end

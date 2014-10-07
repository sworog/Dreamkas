//
//  RESTClient+User.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient+User.h"

#define LOG_ON 1

@implementation RESTClient (User)

/**
 * Аутентификация на сервере по OAuth 2.0
 */
- (void)authWithLogin:(NSString *)login
             password:(NSString *)password
         onCompletion:(DictionaryResponseBlock)completionBlock
{
    NSDictionary *dict = @{@"grant_type" : @"password",
                           @"username" : login,
                           @"password" : password,
                           @"client_id" : @"webfront_webfront",
                           @"client_secret" : @"secret"};
    
    [self POST:@"oauth/v2/token"
    parameters:dict
       success:^(NSURLSessionDataTask * __unused task, id JSON) {
           // проверяем ответ сервера на корректность
           NSError *error = [self detectError:JSON];
           
           // TODO: работаем с токеном
           /*
            JSON: {
            "access_token" = MDM5MTE3MjhjNTZjNzRiOWU4NGUwOWRkZjBjZGMyMTU5NDdiYjI0NTZlMThiODYwNDJkNjliODM1NmNjY2Q5Zg;
            "expires_in" = 86400;
            "refresh_token" = MmFiZTk3YmM1MTIwY2Q2Nzk4OTllN2Q2MzRkYmEwOWFhNzU3ZWYxNzVlMGMxZTc4OWM0ZjM3NGE4NDNlMjMzNg;
            scope = "<null>";
            "token_type" = bearer;
            }
            */
           
           // передаем данные в блок обработки
           if (completionBlock)
               completionBlock(JSON, error);
           
       } failure:^(NSURLSessionDataTask *__unused task, NSError *error) {
           // передаем данные в блок обработки
           if (completionBlock)
               completionBlock(nil, error);
       }];
}

@end

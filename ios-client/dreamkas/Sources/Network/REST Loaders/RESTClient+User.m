//
//  RESTClient+User.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient+User.h"

#define LOG_ON 1
#define OAUTH_CLIENT_ID     @"webfront_webfront"
#define OAUTH_CLIENT_SECRET @"secret"

@implementation RESTClient (User)

/**
 * Аутентификация на сервере по OAuth
 */
- (void)authWithLogin:(NSString *)login
             password:(NSString *)password
         onCompletion:(DictionaryResponseBlock)completionBlock
{
    NSDictionary *dict = @{@"grant_type" : @"password",
                           @"username" : login,
                           @"password" : password,
                           @"client_id" : OAUTH_CLIENT_ID,
                           @"client_secret" : OAUTH_CLIENT_SECRET};
    
    [self obtainOAuthToken:dict
              onCompletion:completionBlock];
}

/**
 * Обновление OAuth токена на клиенте
 */
- (void)reAuth:(DictionaryResponseBlock)completionBlock
{
    NSDictionary *dict = @{@"grant_type" : @"refresh_token",
                           @"client_id" : OAUTH_CLIENT_ID,
                           @"client_secret" : OAUTH_CLIENT_SECRET,
                           @"refresh_token" : refreshOAuthToken};
    [self.requestSerializer setValue:nil
                  forHTTPHeaderField:@"Authorization"];
    
    [self obtainOAuthToken:dict
              onCompletion:completionBlock];
}

- (void)obtainOAuthToken:(NSDictionary *)dict
            onCompletion:(DictionaryResponseBlock)completionBlock
{
    [self POST:@"oauth/v2/token"
    parameters:dict
       success:^(NSURLSessionDataTask * __unused task, id JSON) {
           // проверяем ответ сервера на корректность
           NSError *error = [self detectError:JSON];
           
           if (error == nil) {
               NSString *type = [JSON valueForKeyPath:@"token_type"];
               NSString *token = [JSON valueForKeyPath:@"access_token"];
               [self.requestSerializer setValue:[NSString stringWithFormat:@"%@ %@", type, token]
                             forHTTPHeaderField:@"Authorization"];
               
               // запоминаем дату для обновления токена и refresh-токен
               oauthTokenExpirationDate = [NSDate dateWithTimeIntervalSinceNow:[JSON[@"expires_in"] doubleValue]];
               refreshOAuthToken = JSON[@"refresh_token"];
           }
           
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

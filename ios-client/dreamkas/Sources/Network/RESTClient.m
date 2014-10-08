//
//  RESTClient.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient.h"

#define LOG_ON 1

@implementation RESTClient

#pragma mark - Основные методы

- (instancetype)initWithBaseURL:(NSURL *)url
{
    self = [super initWithBaseURL:url];
    if (!self) {
        return nil;
    }
    // nothing to do here..
    return self;
}

- (NSURLSessionDataTask *)dataTaskWithRequest:(NSURLRequest *)request
                            completionHandler:(void (^)(NSURLResponse *response, id responseObject, NSError *error))completionHandler
{
    __weak typeof(self)weak_self = self;
    
    if ([self tokenNotExpired]) {
        return [super dataTaskWithRequest:request
                        completionHandler:^(NSURLResponse *response, id responseObject, NSError *error) {
                            __strong typeof(self)strong_self = weak_self;
                            [strong_self onRequestCompletion:responseObject responseObject:responseObject
                                                       error:error handler:completionHandler];
                        }];
    }
    
    // если OAuth-токен устарел - обновляем его и выполняем текущий запрос
    refreshingOAuthTokenInProgress = YES;
    __block NSURLSessionDataTask *task = nil;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        
        task = [super dataTaskWithRequest:request
                        completionHandler:^(NSURLResponse *response, id responseObject, NSError *error) {
                            [strong_self onRequestCompletion:responseObject responseObject:responseObject
                                                       error:error handler:completionHandler];
                        }];
    }];
    
    return task;
}

- (BOOL)tokenNotExpired
{
    return (refreshingOAuthTokenInProgress || (oauthTokenExpirationDate == nil) || ([[NSDate date] isLaterThanDate:oauthTokenExpirationDate] == NO));
}

- (void)onRequestCompletion:(NSURLResponse *)response  responseObject:(id)responseObject error:(NSError *)error
                    handler:(void (^)(NSURLResponse *response, id responseObject, NSError *error))completionHandler
{
    DPLog(LOG_ON, @"=== REQUEST %@ ===", (error)?@"FAILURE":@"SUCCESS");
    DPLog(LOG_ON, @"error : %@", error);
    DPLog(LOG_ON, @"response : %@", responseObject);
    
    // передаем данные в блок обработки
    if (completionHandler)
        completionHandler(response, responseObject, error);
}

@end

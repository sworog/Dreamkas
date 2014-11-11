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

- (NSURLSessionDataTask *)dataTaskWithRequest:(NSURLRequest *)request
                            completionHandler:(void (^)(NSURLResponse *response, id responseObject, NSError *error))completionHandler
{
    return [super dataTaskWithRequest:request
                    completionHandler:^(NSURLResponse *response, id responseObject, NSError *error) {
                        DPLog(LOG_ON, @"=== REQUEST %@ ===", (error)?@"FAILURE":@"SUCCESS");
                        DPLog(LOG_ON, @"error : %@", error);
                        DPLog(LOG_ON, @"response : %@", responseObject);
                        
                        // передаем данные в блок обработки
                        if (completionHandler)
                            completionHandler(response, responseObject, error);
                    }];
}

- (BOOL)tokenNotExpired
{
    return (refreshingOAuthTokenInProgress || (oauthTokenExpirationDate == nil) ||
            ([[NSDate date] isLaterThanDate:oauthTokenExpirationDate] == NO));
}

- (NSURLSessionDataTask *)GET:(NSString *)URLString
                   parameters:(id)parameters
                      success:(void (^)(NSURLSessionDataTask *task, id responseObject))success
                      failure:(void (^)(NSURLSessionDataTask *task, NSError *error))failure
{
    if ([self tokenNotExpired])
        return [super GET:URLString parameters:parameters success:success failure:failure];
    
    __weak typeof(self)weak_self = self;
    refreshingOAuthTokenInProgress = YES;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        [super GET:URLString parameters:parameters success:success failure:failure];
    }];
    return nil;
}

- (NSURLSessionDataTask *)HEAD:(NSString *)URLString
                    parameters:(id)parameters
                       success:(void (^)(NSURLSessionDataTask *task))success
                       failure:(void (^)(NSURLSessionDataTask *task, NSError *error))failure
{
    if ([self tokenNotExpired])
        return [super HEAD:URLString parameters:parameters success:success failure:failure];
    
    __weak typeof(self)weak_self = self;
    refreshingOAuthTokenInProgress = YES;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        [super HEAD:URLString parameters:parameters success:success failure:failure];
    }];
    return nil;
}

- (NSURLSessionDataTask *)POST:(NSString *)URLString
                    parameters:(id)parameters
                       success:(void (^)(NSURLSessionDataTask *task, id responseObject))success
                       failure:(void (^)(NSURLSessionDataTask *task, NSError *error))failure
{
    if ([self tokenNotExpired])
        return [super POST:URLString parameters:parameters success:success failure:failure];
    
    __weak typeof(self)weak_self = self;
    refreshingOAuthTokenInProgress = YES;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        [super POST:URLString parameters:parameters success:success failure:failure];
    }];
    return nil;
}

- (NSURLSessionDataTask *)PUT:(NSString *)URLString
                   parameters:(id)parameters
                      success:(void (^)(NSURLSessionDataTask *task, id responseObject))success
                      failure:(void (^)(NSURLSessionDataTask *task, NSError *error))failure
{
    if ([self tokenNotExpired])
        return [super PUT:URLString parameters:parameters success:success failure:failure];
    
    __weak typeof(self)weak_self = self;
    refreshingOAuthTokenInProgress = YES;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        [super PUT:URLString parameters:parameters success:success failure:failure];
    }];
    return nil;
}

- (NSURLSessionDataTask *)PATCH:(NSString *)URLString
                     parameters:(id)parameters
                        success:(void (^)(NSURLSessionDataTask *task, id responseObject))success
                        failure:(void (^)(NSURLSessionDataTask *task, NSError *error))failure
{
    if ([self tokenNotExpired])
        return [super PATCH:URLString parameters:parameters success:success failure:failure];
    
    __weak typeof(self)weak_self = self;
    refreshingOAuthTokenInProgress = YES;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        [super PATCH:URLString parameters:parameters success:success failure:failure];
    }];
    return nil;
}

- (NSURLSessionDataTask *)DELETE:(NSString *)URLString
                      parameters:(id)parameters
                         success:(void (^)(NSURLSessionDataTask *task, id responseObject))success
                         failure:(void (^)(NSURLSessionDataTask *task, NSError *error))failure
{
    if ([self tokenNotExpired])
        return [super DELETE:URLString parameters:parameters success:success failure:failure];
    
    __weak typeof(self)weak_self = self;
    refreshingOAuthTokenInProgress = YES;
    [self reAuth:^(NSDictionary *data, NSError *error) {
        __strong typeof(self)strong_self = weak_self;
        strong_self->refreshingOAuthTokenInProgress = NO;
        [super DELETE:URLString parameters:parameters success:success failure:failure];
    }];
    return nil;
}

@end

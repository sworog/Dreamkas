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
                        
                        // приводим ошибку к стандартному формату приложения
                        if (error)
                            error = [self wrapForError:error withResponse:response responseObject:responseObject];
                        
                        // передаем данные в блок обработки
                        if (completionHandler)
                            completionHandler(response, responseObject, error);
                    }];
}

- (NSError *)wrapForError:(NSError *)error withResponse:(NSURLResponse *)response responseObject:(id)responseObject
{
    DPLogFast(@"");
    
    // вытаскиваем HTTP код ошибки
    NSInteger code = [(NSHTTPURLResponse *)response statusCode];
    
    // смотрим тело ответа сервера
    if ([responseObject isKindOfClass:[NSDictionary class]] == NO) {
        NSError *wrapped_error = [NSError errorWithDomain:[error domain] code:code
                                                 userInfo:@{@"message" : @"", @"reason" : @""}];
        return wrapped_error;
    }
    
    // пробуем найти точную причину ошибки
    NSDictionary *dict = (NSDictionary *)responseObject;
    NSString *message = dict[@"error"]?dict[@"error"]:dict[@"message"];
    NSString *reason = @"";
    NSString *plain_dict = [NSString stringWithFormat:@"%@", dict[@"errors"]];
    
    NSRegularExpression *regex = [NSRegularExpression regularExpressionWithPattern:@"errors =\\s*\\(\\s*\"[^\"]*\"\\s*\\);"
                                                                           options:NSRegularExpressionCaseInsensitive error:&error];
    NSArray *matches = [regex matchesInString:plain_dict options:0 range:NSMakeRange(0, [plain_dict length])];
    if ([matches count] > 0) {
        NSTextCheckingResult *chres = [matches firstObject];
        NSString *unicode_str = [plain_dict substringWithRange:[chres range]];
        reason = [NSString stringWithCString:[unicode_str cStringUsingEncoding:NSUTF8StringEncoding]
                                    encoding:NSNonLossyASCIIStringEncoding];
        reason = [reason stringByReplacingOccurrencesOfString:@"\n" withString:@" "];
        reason = [reason stringByReplacingOccurrencesOfString:@" +" withString:@" " options:NSRegularExpressionSearch
                                                        range:NSMakeRange(0, [reason length])];
    }
    
    NSError *wrapped_error = [NSError errorWithDomain:[error domain] code:code
                                             userInfo:@{@"message" : message, @"reason" : reason}];
    return wrapped_error;
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

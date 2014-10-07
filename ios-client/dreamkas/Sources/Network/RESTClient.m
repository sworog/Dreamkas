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
                            completionHandler:(void (^)(NSURLResponse *, id, NSError *))completionHandler
{
    if (oauthTokenExpirationDate && [[NSDate date] isLaterThanDate:oauthTokenExpirationDate]) {
        // если необходимо обновить OAuth-токен, то сперва обновляем его
        [self reAuth:^(NSDictionary *data, NSError *error) {
            // TODO: рекурсия не пропустит вызов!!!!
            // ..
        }];
    }
    
    return [super dataTaskWithRequest:request
                    completionHandler:^(NSURLResponse *response, id responseObject, NSError *error) {
                        if (error) {
                            DPLog(LOG_ON, @"=== REQUEST FAILURE ===");
                            DPLog(LOG_ON, @"error : %@", error);
                        }
                        else {
                            DPLog(LOG_ON, @"=== REQUEST SUCCESS ===");
                            DPLog(LOG_ON, @"response : %@", responseObject);
                        }
                        
                        // передаем данные в блок обработки
                        if (completionHandler)
                            completionHandler(response, responseObject, error);
                    }];
}

/**
 * Обобщенный запрос к серверу на скачивание файла
 */
- (void)downloadRequest:(NSString *)path
           onCompletion:(ResponseBlock)completionBlock
{
    NSURLSessionConfiguration *configuration = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:path]];
    NSURLSession *session = [NSURLSession sessionWithConfiguration:configuration delegate:nil
                                                     delegateQueue:[NSOperationQueue currentQueue]];
    
    NSURLSessionDataTask *task =
    [session dataTaskWithRequest:request
               completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
     {
         // передаем данные в блок обработки
         if (completionBlock)
             completionBlock(data, error);
     }];
    
    [task resume];
}

@end

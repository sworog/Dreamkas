//
//  RESTClient.h
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AFHTTPSessionManager.h"

@interface RESTClient : AFHTTPSessionManager
{
    NSDictionary *defaultParameters;
    
    NSString *refreshOAuthToken;
    NSDate *oauthTokenExpirationDate;
}

/**
 * Обобщенный GET запрос к серверу
 */
- (void)GETRequest:(NSString *)path
            params:(NSDictionary *)params
      onCompletion:(ResponseBlock)completionBlock;

/**
 * Обобщенный POST запрос к серверу
 */
- (void)POSTRequest:(NSString *)path
             params:(NSDictionary *)params
       onCompletion:(ResponseBlock)completionBlock;

/**
 * Обобщенный запрос к серверу на скачивание файла
 */
- (void)downloadRequest:(NSString *)path
           onCompletion:(ResponseBlock)completionBlock;

/**
 * Метод обобщенной детекции ошибок в ответах сервера и их "обёртке" в привычный вид
 */
- (NSError *)detectError:(id)JSON;

@end

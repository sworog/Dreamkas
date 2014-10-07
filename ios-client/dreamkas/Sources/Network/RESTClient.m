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
    
    // формируем стандартный набор параметров запроса
    defaultParameters = @{@"client_type" : @"iOS",
                          @"client_version" : APP_VERSION};
    
    return self;
}

/**
 * Обобщенный GET запрос к серверу
 */
- (void)GETRequest:(NSString *)path
            params:(NSDictionary *)params
      onCompletion:(ResponseBlock)completionBlock
{
    [self GET:[self prepareRequestURL:path]
   parameters:[self prepareRequestParams:params]
      success:^(NSURLSessionDataTask * __unused task, id JSON) {
          // проверяем ответ сервера на корректность
          NSError *error = [self detectError:JSON];
          
          // передаем данные в блок обработки
          if (completionBlock)
              completionBlock(JSON, error);
          
      } failure:^(NSURLSessionDataTask *__unused task, NSError *error) {
          // передаем данные в блок обработки
          if (completionBlock)
              completionBlock(nil, error);
      }];
}

/**
 * Обобщенный POST запрос к серверу
 */
- (void)POSTRequest:(NSString *)path
             params:(NSDictionary *)params
       onCompletion:(ResponseBlock)completionBlock
{
    [self POST:[self prepareRequestURL:path]
    parameters:[self prepareRequestParams:params]
       success:^(NSURLSessionDataTask * __unused task, id JSON) {
           // проверяем ответ сервера на корректность
           NSError *error = [self detectError:JSON];
           
           // передаем данные в блок обработки
           if (completionBlock)
               completionBlock(JSON, error);
           
       } failure:^(NSURLSessionDataTask *__unused task, NSError *error) {
           // передаем данные в блок обработки
           if (completionBlock)
               completionBlock(nil, error);
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

#pragma mark - Вспомогательные методы

/**
 * Метод для конфигурирования URL'a запроса
 */
- (NSString*)prepareRequestURL:(NSString*)localPath
{
    return [NSString stringWithFormat:@"%@%@", API_SERVER_PATH, localPath];
}

/**
 * Метод для конфигурирования параметров запроса
 */
- (NSDictionary *)prepareRequestParams:(NSDictionary *)params
{
    NSMutableDictionary *m_params = [defaultParameters mutableCopy];
    
    // добавляем параметры текущего запроса
    if (params)
        [m_params addEntriesFromDictionary:params];
    
    // TODO: добавляем токен пользователя
    // ..
    
    return [m_params copy];
}

/**
 * Метод обобщенной детекции ошибок в ответах сервера и их "обёртке" в привычный вид
 */
- (NSError *)detectError:(id)JSON
{
    if ([JSON isKindOfClass:[NSDictionary class]]) {
        NSDictionary *dict = JSON;
        id error_in_response = [dict valueForKeyPath:@"error"];
        
        if (error_in_response || (dict == nil)) {
            NSString *err_descr = [dict valueForKeyPath:@"error_description"];
            DPLog(LOG_ON, @"=== REQUEST FAILURE ===");
            DPLog(LOG_ON, @"error_status : %@", error_in_response);
            DPLog(LOG_ON, @"error_description : %@", err_descr);
            return [NSError errorWithDomain:err_descr
                                       code:400
                                   userInfo:nil];
        }
        else {
            DPLog(LOG_ON, @"=== REQUEST SUCCESS ===");
            DPLog(LOG_ON, @"response : %@", dict);
        }
    }
    else {
        DPLog(LOG_ON, @"=== REQUEST FAILURE ===");
        DPLog(LOG_ON, @"response class is not equal to NSDictionary");
        return [NSError errorWithDomain:@"Response Failure"
                                   code:999
                               userInfo:nil];
    }
    return nil;
}

@end

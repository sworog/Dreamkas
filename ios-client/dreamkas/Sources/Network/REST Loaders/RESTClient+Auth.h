//
//  RESTClient+Auth.h
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient.h"

@interface RESTClient (Auth)

/**
 * Аутентификация на сервере по OAuth
 */
- (void)authWithLogin:(NSString *)login
             password:(NSString *)password
         onCompletion:(DictionaryResponseBlock)completionBlock;

/**
 * Обновление OAuth токена на клиенте
 */
- (void)reAuth:(DictionaryResponseBlock)completionBlock;

@end

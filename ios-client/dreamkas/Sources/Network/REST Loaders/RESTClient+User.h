//
//  RESTClient+User.h
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient.h"

@interface RESTClient (User)

/**
 * Аутентификация на сервере по OAuth 2.0
 */
- (void)authWithLogin:(NSString *)login
             password:(NSString *)password
         onCompletion:(DictionaryResponseBlock)completionBlock;

@end

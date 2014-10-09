//
//  RESTClient.h
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AFHTTPSessionManager.h"

#if API_USE_TEST_SERVER
    #define CompleteURL(lpath)  [NSString stringWithFormat:@"%@%@", API_TEST_SERVER_PATH, lpath]
#else
    #define CompleteURL(lpath)  [NSString stringWithFormat:@"%@%@", API_SERVER_PATH, lpath]
#endif

@interface RESTClient : AFHTTPSessionManager
{
    NSString *refreshOAuthToken;
    NSDate *oauthTokenExpirationDate;
    BOOL refreshingOAuthTokenInProgress;
}

@end

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
    NSString *refreshOAuthToken;
    NSDate *oauthTokenExpirationDate;
    BOOL refreshingOAuthTokenInProgress;
}

@end

//
//  RESTClient.h
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AFHTTPSessionManager.h"

#define CompleteURL(lpath) [NSString stringWithFormat:@"%@%@", API_SERVER_PATH, lpath]

@interface RESTClient : AFHTTPSessionManager
{
    NSString *refreshOAuthToken;
    NSDate *oauthTokenExpirationDate;
}

@end

//
//  RESTClient+Groups.h
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient.h"

@interface RESTClient (Groups)

/**
 * Получение списка групп
 */
- (void)requestGroups:(ArrayResponseBlock)completionBlock;

@end

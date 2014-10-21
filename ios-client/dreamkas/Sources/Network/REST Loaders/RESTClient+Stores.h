//
//  RESTClient+Stores.h
//  dreamkas
//
//  Created by sig on 15.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient.h"

@interface RESTClient (Stores)

/**
 * Получение списка магазинов
 */
- (void)requestStores:(ArrayResponseBlock)completionBlock;

@end

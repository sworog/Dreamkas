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

/**
 * Получение списка групп продуктов
 */
- (void)requestGroups:(ArrayResponseBlock)completionBlock;

/**
 * Получение продуктов из конкретной группы
 */
- (void)requestProductsFromGroup:(NSString *)groupId
                    onCompletion:(ArrayResponseBlock)completionBlock;

/**
 * Получение продуктов по названию, SKU или штрих-коду
 */
- (void)requestProductsByQuery:(NSString *)queryValue
                  onCompletion:(ArrayResponseBlock)completionBlock;

@end

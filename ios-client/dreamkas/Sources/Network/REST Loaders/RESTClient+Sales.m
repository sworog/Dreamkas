//
//  RESTClient+Sales.m
//  dreamkas
//
//  Created by sig on 04.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "RESTClient+Sales.h"

#define LOG_ON 1

@implementation RESTClient (Sales)

/**
 * Отправка запроса на оплату чека
 */
- (void)sendPayment:(NSString *)amountTendered
        paymentType:(NSString *)paymentType
       onCompletion:(ArrayResponseBlock)completionBlock
{
    NSString *str = [NSString stringWithFormat:@"stores/%@/sales", [CurrentUser lastUsedStoreID]];
    
    NSMutableArray *products_array = [NSMutableArray array];
    for (SaleItemModel *si in [SaleItemModel MR_findAll]) {
        ProductModel *product = [ProductModel findByPK:[si productId]];
        [products_array addObject:@{@"price" : [product sellingPrice],
                                    @"product" : [si productId],
                                    @"quantity" : [si quantity]}];
    }
    
    NSMutableDictionary *payment_dict = [NSMutableDictionary dictionary];
    payment_dict[@"type"] = paymentType;
    if ([paymentType isEqualToString:kPaymentTypeCash])
        payment_dict[@"amountTendered"] = amountTendered;
    
    NSDictionary *dict = @{@"date" : [[DatesHelper defaultDateFormatter] stringFromDate:[NSDate date]],
                           @"payment" : payment_dict,
                           @"products" : products_array};
    
    DPLogFast(@"dict = %@", dict);
    
    [self POST:CompleteURL(str)
    parameters:dict
       success:^(NSURLSessionDataTask *task, id responseObject) {
           DPLog(LOG_ON, @"Получили распарсенный ответ сервера");
           
           // маппинг полученных данных в экземпляры сущностей
           NSArray *models = nil;//[GroupModel mapModelsFromList:JSON];
           
           DPLog(LOG_ON, @"Закончили маппинг ответа сервера");
           
           // сохраняем экземпляры сущностей в БД
           [[NSManagedObjectContext MR_defaultContext] MR_saveToPersistentStoreAndWait];
           
           DPLog(LOG_ON, @"Сохранили изменения в БД");
           
           completionBlock(models, nil);
       } failure:^(NSURLSessionDataTask *task, NSError *error) {
           // передаем данные в блок обработки
           if (completionBlock)
               completionBlock(nil, error);
       }];
}

@end

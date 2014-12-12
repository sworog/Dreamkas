//
//  BlockDefines.h
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#ifndef dreamkas_BlockDefines_h
#define dreamkas_BlockDefines_h

@class AbstractModel;

typedef void (^VoidResponseBlock)();

typedef void (^ResponseBlock)(id data, NSError *error);
typedef void (^ModelResponseBlock)(AbstractModel *object, NSError *error);

typedef void (^ArrayResponseBlock)(NSArray *data, NSError *error);
typedef void (^DictionaryResponseBlock)(NSDictionary *data, NSError *error);

typedef void (^StringResponseBlock)(NSString *data, NSError *error);
typedef void (^NumberResponseBlock)(NSNumber *number, NSError *error);
typedef void (^LogicalResponseBlock)(BOOL flag, NSError *error);

typedef void (^ErrorResponseBlock)(NSError *error);

typedef void (^TransmitProgressBlock)(NSInteger bytesTransmited, long long totalBytesTransmited, long long totalBytesExpectedToTransmit);

#endif

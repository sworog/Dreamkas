//
//  BlockDefines.h
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#ifndef dreamkas_BlockDefines_h
#define dreamkas_BlockDefines_h

typedef void (^VoidResponseBlock)();

typedef void (^ResponseBlock)(id data);
//typedef void (^ModelResponseBlock)(AbstractModel *object);

typedef void (^ArrayResponseBlock)(NSArray *data);
typedef void (^DictionaryResponseBlock)(NSDictionary *data);

typedef void (^StringResponseBlock)(NSString *data);
typedef void (^NumberResponseBlock)(NSNumber *number);
typedef void (^LogicalResponseBlock)(BOOL flag);

typedef void (^ErrorResponseBlock)(NSError *error);

typedef void (^TransmitProgressBlock)(NSInteger bytesTransmited, long long totalBytesTransmited, long long totalBytesExpectedToTransmit);

#endif

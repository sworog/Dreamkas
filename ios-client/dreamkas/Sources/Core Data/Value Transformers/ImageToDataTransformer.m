//
//  ImageToDataTransformer.m
//  dreamkas
//
//  Created by sig on 07.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ImageToDataTransformer.h"

@implementation ImageToDataTransformer

/**
 *  Получатель может обратить преобразование
 */
+ (BOOL)allowsReverseTransformation
{
	return YES;
}

/**
 *  Класс объекта, полученного при обратном преобразовании получателем
 */
+ (Class)transformedValueClass
{
	return [NSData class];
}

/**
 *  Возвращает результат прямого преобразования объекта
 */
- (id)transformedValue:(id)value
{
	return UIImagePNGRepresentation(value);
}

/**
 *  Возвращает результат обратного преобразования объекта
 */
- (id)reverseTransformedValue:(id)value
{
	return [[UIImage alloc] initWithData:value];
}

@end
//
//  ProductCell.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ProductCell.h"

@implementation ProductCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    [super awakeFromNib];
    
    [self.titleLabel setFont:DefaultFont(16)];
    [self.titleLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.87]];
    
    [self.priceLabel setFont:DefaultFont(16)];
    [self.priceLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.87]];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(ProductModel *)model
{
    [self.titleLabel setText:[[model name] lowercaseStringWithFirstUppercaseLetter]];
    [self.titleLabel setY:DefaultVerticalCellInsets];
    
    NSNumberFormatter *formatter = [NSNumberFormatter new];
    [formatter setNumberStyle:NSNumberFormatterDecimalStyle];
    [formatter setGroupingSeparator:@" "];
    [formatter setGroupingSize:3];
    [formatter setDecimalSeparator:@","];
    [formatter setAlwaysShowsDecimalSeparator:YES];
    [formatter setUsesGroupingSeparator:YES];
    [formatter setMaximumFractionDigits:2];
    [formatter setMinimumFractionDigits:2];
    
    NSMutableString *str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:[model sellingPrice]]];
    [self.priceLabel setText:str];
    [self.priceLabel setY:DefaultVerticalCellInsets];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.priceLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

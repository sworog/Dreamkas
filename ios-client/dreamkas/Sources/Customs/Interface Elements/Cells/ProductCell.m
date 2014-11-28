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
    
    [self.priceLabel setFont:DefaultMediumFont(16)];
    [self.priceLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.87]];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(ProductModel *)model
{
    [self.titleLabel setText:[model name]];
    [self.titleLabel setY:DefaultVerticalCellInsets];
    
    PriceNumberFormatter *formatter = [PriceNumberFormatter new];    
    NSMutableString *str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:[model sellingPrice]]];
    [self.priceLabel setText:str];
    [self.priceLabel setY:DefaultVerticalCellInsets];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.priceLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

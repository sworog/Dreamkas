//
//  SaleItemCell.m
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SaleItemCell.h"

@implementation SaleItemCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    [super awakeFromNib];
    
    [self.priceLabel setFont:DefaultMediumFont(16)];
    [self.priceLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.54]];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(SaleItemModel *)model
{
    ProductModel *product = [ProductModel findByPK:[model productId]];
    
    // установка наименования и прочих параметров
    
    [self.titleLabel setY:DefaultVerticalCellInsets];
    NSMutableAttributedString *m_attr_str = [[NSMutableAttributedString alloc] initWithString:[product name]
                                                                                   attributes:@{NSFontAttributeName:DefaultFont(16),
                                                                                                NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.54]}];
    [m_attr_str appendAttributedString:[[NSMutableAttributedString alloc] initWithString:[NSString stringWithFormat:@" x %g %@", [[model quantity] floatValue], [product units]]
                                                                              attributes:@{NSFontAttributeName:DefaultLightFont(14),
                                                                                           NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.54]}]];
    [self.titleLabel setAttributedText:m_attr_str];
    
    // установка цены
    
    NSNumberFormatter *formatter = [NSNumberFormatter new];
    [formatter setNumberStyle:NSNumberFormatterDecimalStyle];
    [formatter setGroupingSeparator:@" "];
    [formatter setGroupingSize:3];
    [formatter setDecimalSeparator:@","];
    [formatter setAlwaysShowsDecimalSeparator:YES];
    [formatter setUsesGroupingSeparator:YES];
    [formatter setMaximumFractionDigits:2];
    [formatter setMinimumFractionDigits:2];
    
    [self.priceLabel setHidden:YES];
    if ([[product sellingPrice] doubleValue] > 0.0f) {
        [self.priceLabel setHidden:NO];
    }
    
    NSMutableString *str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:[product sellingPrice]]];
    [self.priceLabel setText:str];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.priceLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

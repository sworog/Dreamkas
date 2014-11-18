//
//  SaleItemCell.m
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "SaleItemCell.h"

#define TitleHeight 22.f
#define SpaceBetween 16.f

@implementation SaleItemCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    [super awakeFromNib];
    
    unichar ch = 0xf02b;
    [self.tagLabel setText:[NSString stringWithFormat:@"%C", ch]];
    [self.tagLabel setFont:DefaultAwesomeFont(14)];
    [self.tagLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.54]];
    
    [self.priceLabel setFont:DefaultMediumFont(14)];
    [self.priceLabel setTextColor:[DefaultBlackColor colorWithAlphaComponent:0.87]];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(SaleItemModel *)model
{
    ProductModel *product = [ProductModel findByPK:[model productId]];
    
    // установка наименования и прочих параметров
    
    [self.titleLabel setY:DefaultVerticalCellInsets];
    [self.titleLabel setHeight:TitleHeight];
    NSMutableAttributedString *m_attr_str = [[NSMutableAttributedString alloc] initWithString:[product name]
                                                                                   attributes:@{NSFontAttributeName:DefaultFont(14),
                                                                                                NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.87]}];
    if ([[model quantity] floatValue] > 1.0f) {
        [m_attr_str appendAttributedString:[[NSMutableAttributedString alloc] initWithString:[NSString stringWithFormat:@" x%g", [[model quantity] floatValue]]
                                                                                  attributes:@{NSFontAttributeName:DefaultFont(12),
                                                                                               NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.54]}]];
    }
    
    [self.titleLabel setAttributedText:m_attr_str];
    
    // установка цены
    
    [self.priceLabel setHidden:YES];
    if ([[product sellingPrice] doubleValue] > 0.0f) {
        [self.priceLabel setHidden:NO];
    }
    
    PriceNumberFormatter *formatter = [PriceNumberFormatter new];
    NSMutableString *str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:[product sellingPrice]]];
    [self.priceLabel setText:str];
    [self.priceLabel setHeight:self.titleLabel.height];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.priceLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

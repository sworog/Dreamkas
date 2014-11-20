//
//  ProductSearchCell.m
//  dreamkas
//
//  Created by sig on 30.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "ProductSearchCell.h"
#import "SearchViewController.h"

@implementation ProductSearchCell

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
- (CGFloat)configureWithModel:(ProductModel *)model
{
    // установка наименования и прочих параметров
    
    [self.titleLabel setY:DefaultVerticalCellInsets];
    NSString *search_substring = [UserDefaults objectForKey:kSearchViewControllerSearchFieldKey];
    NSMutableAttributedString *m_attr_str = [[NSMutableAttributedString alloc] initWithString:[model name]
                                                                                   attributes:@{NSFontAttributeName:DefaultFont(16),
                                                                                                NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.54]}];
    if ([search_substring length]) {
        [m_attr_str setAttributes:@{NSFontAttributeName:DefaultFont(16),
                                    NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.87]}
                            range:[[model name] rangeOfString:search_substring options:NSCaseInsensitiveSearch]];
    }
    if ([[model sku] length]) {
        NSMutableAttributedString *sku_str = [[NSMutableAttributedString alloc] initWithString:[NSString stringWithFormat:@"\n%@", [model sku]]
                                                                      attributes:@{NSFontAttributeName:DefaultFont(12),
                                                                                   NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.54]}];
        if ([search_substring length]) {
            [sku_str setAttributes:@{NSFontAttributeName:DefaultFont(12),
                                        NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.87]}
                                range:[[sku_str string] rangeOfString:search_substring options:NSCaseInsensitiveSearch]];
        }
        [m_attr_str appendAttributedString:sku_str];
    }
    if ([[model barcode] length]) {
        NSMutableAttributedString *barcode_str = [[NSMutableAttributedString alloc] initWithString:[NSString stringWithFormat:@" / %@", [model barcode]]
                                                                                    attributes:@{NSFontAttributeName:DefaultFont(12),
                                                                                                 NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.54]}];
        if ([search_substring length]) {
            [barcode_str setAttributes:@{NSFontAttributeName:DefaultFont(12),
                                     NSForegroundColorAttributeName:[DefaultBlackColor colorWithAlphaComponent:0.87]}
                             range:[[barcode_str string] rangeOfString:search_substring options:NSCaseInsensitiveSearch]];
        }
        [m_attr_str appendAttributedString:barcode_str];
    }
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
    if ([[model sellingPrice] doubleValue] > 0.0f) {
        [self.priceLabel setHidden:NO];
    }
    
    NSMutableString *str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:[model sellingPrice]]];
    [self.priceLabel setText:str];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.priceLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

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
    
    [self.titleLabel setFont:DefaultFont(18)];
    [self.titleLabel setTextColor:DefaultBlackColor];
    
    [self.priceLabel setFont:DefaultFont(18)];
    [self.priceLabel setTextColor:DefaultBlackColor];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(ProductModel *)model
{
    // установка наименования и прочих параметров
    
    NSString *search_substring = [UserDefaults objectForKey:kSearchViewControllerSearchFieldKey];
    
    [self.titleLabel setY:DefaultVerticalCellInsets];    
    NSMutableAttributedString *m_attr_str = [[NSMutableAttributedString alloc] initWithString:[[model name] lowercaseStringWithFirstUppercaseLetter]
                                                                                   attributes:@{NSFontAttributeName:DefaultFont(18),
                                                                                                NSForegroundColorAttributeName:DefaultBlackColor}];
    if ([search_substring length]) {
        [m_attr_str setAttributes:@{NSFontAttributeName:DefaultBoldFont(18),NSForegroundColorAttributeName:DefaultBlackColor}
                            range:[[model name] rangeOfString:search_substring options:NSCaseInsensitiveSearch]];
    }
    
    if ([[model sku] length]) {
        [m_attr_str appendAttributedString:[[NSAttributedString alloc] initWithString:[NSString stringWithFormat:@"\n%@", [model sku]]
                                                                           attributes:@{NSFontAttributeName:DefaultLightFont(16),
                                                                                        NSForegroundColorAttributeName:DefaultGrayColor}]];
    }
    if ([[model barcode] length]) {
        [m_attr_str appendAttributedString:[[NSAttributedString alloc] initWithString:[NSString stringWithFormat:@" / %@", [model barcode]]
                                                                           attributes:@{NSFontAttributeName:DefaultLightFont(16),
                                                                                        NSForegroundColorAttributeName:DefaultGrayColor}]];
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
    
    NSMutableString *str = [NSMutableString stringWithFormat:@"%@ ₽", [formatter stringFromNumber:[model sellingPrice]]];
    [self.priceLabel setText:str];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.priceLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

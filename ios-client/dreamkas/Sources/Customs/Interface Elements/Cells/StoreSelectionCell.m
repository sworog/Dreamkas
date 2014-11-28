//
//  StoreSelectionCell.m
//  dreamkas
//
//  Created by sig on 30.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "StoreSelectionCell.h"

@implementation StoreSelectionCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    [super awakeFromNib];
    
    [self.titleLabel setFont:DefaultFont(18)];
    [self.titleLabel setTextColor:DefaultBlackColor];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(StoreModel *)model
{
    [self.titleLabel setText:[model name]];
    [self.titleLabel setY:DefaultVerticalCellInsets];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    
    return cell_height;
}

@end

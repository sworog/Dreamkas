//
//  GroupCell.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "GroupCell.h"

#define VerticalCellInsets 17.f

@implementation GroupCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    [super awakeFromNib];
    
    [self.titleLabel setFont:DefaultFont(18)];
    [self.titleLabel setTextColor:DefaultCyanColor];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(GroupModel *)model
{
    [self.titleLabel setText:[model name]];
    [self.titleLabel setY:VerticalCellInsets];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + VerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    
    return cell_height;
}

@end

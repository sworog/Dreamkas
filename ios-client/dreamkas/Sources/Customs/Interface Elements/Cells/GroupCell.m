//
//  GroupCell.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "GroupCell.h"

@implementation GroupCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    [super awakeFromNib];
    
    [self.titleLabel setFont:DefaultFont(16)];
    [self.titleLabel setTextColor:DefaultLightCyanColor];
    
    unichar ch = 0xf054;
    [self.arrowLabel setText:[NSString stringWithFormat:@"%C", ch]];
    [self.arrowLabel setFont:DefaultAwesomeFont(16)];
    [self.arrowLabel setTextColor:DefaultDarkGrayColor];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(GroupModel *)model
{
    [self.titleLabel setText:[model name]];
    [self.titleLabel setY:DefaultVerticalCellInsets];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.arrowLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

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
    
    [self.titleLabel setFont:DefaultFont(18)];
    [self.titleLabel setTextColor:DefaultCyanColor];
    
    [self.arrowLabel setText:@"❯"];
    [self.arrowLabel setFont:DefaultFont(18)];
    [self.arrowLabel setTextColor:DefaultDarkGrayColor];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(GroupModel *)model
{
    [self.titleLabel setText:[[model name] lowercaseStringWithFirstUppercaseLetter]];
    [self.titleLabel setY:DefaultVerticalCellInsets];
    
    CGFloat cell_height = CGRectGetMaxY(self.titleLabel.frame) + DefaultVerticalCellInsets;
    self.cellSeparator.y = (float)(cell_height - DefaultCellSeparatorHeight);
    self.arrowLabel.centerY = (float)cell_height/2.f;
    
    return cell_height;
}

@end

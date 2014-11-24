//
//  CustomTableViewCell.m
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomTableViewCell.h"

#define LOG_ON 0

@implementation CustomTableViewCell

#pragma mark - Основная логика

/**
 *  Однократная конфигурация элементов ячейки после их загрузки из xib'a
 */
- (void)awakeFromNib
{
    DPLog(LOG_ON, @"");
    
    [super awakeFromNib];
    
    [self setSelectionStyle:UITableViewCellSelectionStyleGray];
    [self setSeparatorInset:UIEdgeInsetsMake(0, 0, 0, 0)];
    
    self.backgroundColor = DefaultWhiteColor;
    self.contentView.backgroundColor = [UIColor clearColor];
    
    self.selectedBackground = [[UIView alloc] initWithFrame:self.contentView.bounds];
    [self.selectedBackground setAutoresizingMask:self.contentView.autoresizingMask];
    [self.selectedBackground setBackgroundColor:DefaultLightGrayColor];
    [self setSelectedBackgroundView:self.selectedBackground];
    
    self.cellSeparator = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.contentView.width, DefaultCellSeparatorHeight)];
    [self.cellSeparator setBackgroundColor:DefaultLightGrayColor];
    [self.contentView addSubview:self.cellSeparator];
    
    // addition me
}

/**
 * Рассчет высоты ячейки согласно её содержимому
 */
+ (CGFloat)cellHeight:(UITableView *)tableView cellIdentifier:(NSString*)cellId model:(NSManagedObject*)model
{
    DPLog(LOG_ON, @"");
    
    UITableViewCell<CustomDataCellDelegate> *cell = [tableView dequeueReusableCellWithIdentifier:cellId];
    return [cell configureWithModel:model];
}

/**
 * Настройка ячейки данными модели
 */
- (CGFloat)configureWithModel:(NSManagedObject *)model
{
    DPLog(LOG_ON, @"");
    
    // redefine me
    
    return DefaultSingleLineCellHeight;
}

#pragma mark - Методы реакции на смену состояния ячейки

- (void)setHighlighted:(BOOL)highlighted animated:(BOOL)animated
{
    DPLog(LOG_ON, @"highlighted = %d", highlighted);
    
    [super setHighlighted:highlighted animated:animated];
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    DPLog(LOG_ON, @"selected = %d", selected);
    
    [super setSelected:selected animated:animated];
}

@end

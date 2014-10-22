//
//  CustomTableViewCell.h
//  dreamkas
//
//  Created by sig on 09.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CustomTableViewCell : UITableViewCell <CustomDataCellDelegate>

/** Фон ячейки под нажатое состояние */
@property (nonatomic) UIView *selectedBackground;

/** Разделитель ячейки */
@property (nonatomic) IBOutlet UIView *cellSeparator;

@end

//
//  SaleItemCell.h
//  dreamkas
//
//  Created by sig on 18.11.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomTableViewCell.h"

@interface SaleItemCell : CustomTableViewCell

/** Название продуктовой единицы */
@property (nonatomic, weak) IBOutlet UILabel *titleLabel;

/** Поле тэга для обозначения скидки или др. */
@property (nonatomic, weak) IBOutlet UILabel *tagLabel;

/** Стоимость продуктовой единицы */
@property (nonatomic, weak) IBOutlet UILabel *priceLabel;

@end

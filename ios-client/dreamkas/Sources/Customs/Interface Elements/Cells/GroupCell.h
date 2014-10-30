//
//  GroupCell.h
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomTableViewCell.h"

@interface GroupCell : CustomTableViewCell

/** Название продуктовой группы */
@property (nonatomic, weak) IBOutlet WordWrappingLabel *titleLabel;

/** Стрелка */
@property (nonatomic, weak) IBOutlet UILabel *arrowLabel;

@end

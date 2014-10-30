//
//  StoreSelectionCell.h
//  dreamkas
//
//  Created by sig on 30.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomTableViewCell.h"

@interface StoreSelectionCell : CustomTableViewCell

/** Название магазина */
@property (nonatomic, weak) IBOutlet WordWrappingLabel *titleLabel;

@end

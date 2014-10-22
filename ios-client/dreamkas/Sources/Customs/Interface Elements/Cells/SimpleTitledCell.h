//
//  SimpleTitledCell.h
//  dreamkas
//
//  Created by sig on 17.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomTableViewCell.h"

@interface SimpleTitledCell : CustomTableViewCell

/** Название пособия */
@property (nonatomic, weak) IBOutlet WordWrappingLabel *titleLabel;

@end

//
//  CustomNavigationBar.m
//  dreamkas
//
//  Created by sig on 29.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "CustomNavigationBar.h"

@implementation CustomNavigationBar

- (id)init
{
    self = [super init];
    if (self) {
        [self initialize];
    }
    return self;
}

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        [self initialize];
    }
    return self;
}

- (id)initWithCoder:(NSCoder *)aDecoder
{
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self initialize];
    }
    return self;
}

- (void)initialize
{
    // Цвет фона задается из вне
    //[self setBarTintColor:DefaultLightGrayColor];
    
    // Для текста устанавливается только семейство шрифтов, цвет задается из вне
    [self setTitleTextAttributes:@{NSFontAttributeName:DefaultMediumFont(20),
                                   NSForegroundColorAttributeName:[self.titleTextAttributes[NSForegroundColorAttributeName] colorWithAlphaComponent:0.87]}];
    
    // Для заголовка задается смещение по вертикали
    [[UINavigationBar appearance] setTitleVerticalPositionAdjustment:-10
                                                       forBarMetrics:UIBarMetricsDefault];
    
    // Убираем нижнюю тень
//    [[UINavigationBar appearance] setShadowImage:[UIImage new]];
//    [[UINavigationBar appearance] setBackgroundImage:[UIImage new] forBarMetrics:UIBarMetricsDefault];
}

- (void)layoutSubviews
{
    [super layoutSubviews];
    
    // Устанавливается фиксированная нестандартная высота
    [self setHeight:DefaultTopPanelHeight];
    
    // После чего производится перепозиционирование всех боковых элементов
    UINavigationItem *navigation_item = [self topItem];
    for (UIView *subview in [self subviews]) {
        if (subview == [[navigation_item rightBarButtonItem] customView]) {
            [subview setCenter:CGPointMake(self.width-subview.width/2, self.height/2)];
        } else if (subview == [[navigation_item leftBarButtonItem] customView]) {
            [subview setCenter:CGPointMake(subview.width/2, self.height/2)];
        }
    }
}

@end

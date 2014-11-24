//
//  AppDelegate.h
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import <UIKit/UIKit.h>

@class RESTClient;
@interface AppDelegate : UIResponder <UIApplicationDelegate>

@property (nonatomic, strong) UIWindow *window;
@property (nonatomic, strong) UITapGestureRecognizer *tapGestureRecognizer;

/** Шлюз клиент-серверного взаимодействия */
@property (nonatomic, strong) RESTClient *networkManager;

@end


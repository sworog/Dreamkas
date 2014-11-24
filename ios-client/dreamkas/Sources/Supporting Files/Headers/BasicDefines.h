//
//  BasicDefines.h
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#ifndef dreamkas_BasicDefines_h
#define dreamkas_BasicDefines_h

//
// Макросы-обертки
//

#define ApplicationDelegate                 ((AppDelegate *)[[UIApplication sharedApplication] delegate])
#define UserDefaults                        [NSUserDefaults standardUserDefaults]
#define SharedApplication                   [UIApplication sharedApplication]
#define Bundle                              [NSBundle mainBundle]

#define MainScreen                          [UIScreen mainScreen]
#define ScreenWidth                         [[UIScreen mainScreen] bounds].size.width
#define ScreenHeight                        [[UIScreen mainScreen] bounds].size.height

#define NavBar                              self.navigationController.navigationBar
#define TabBar                              self.tabBarController.tabBar
#define NavBarHeight                        self.navigationController.navigationBar.bounds.size.height
#define TabBarHeight                        self.tabBarController.tabBar.bounds.size.height

#define ViewWidth(v)                        v.frame.size.width
#define ViewHeight(v)                       v.frame.size.height
#define ViewX(v)                            v.frame.origin.x
#define ViewY(v)                            v.frame.origin.y
#define SelfViewWidth                       self.view.bounds.size.width
#define SelfViewHeight                      self.view.bounds.size.height

#define RectX(f)                            f.origin.x
#define RectY(f)                            f.origin.y
#define RectWidth(f)                        f.size.width
#define RectHeight(f)                       f.size.height
#define RectSetWidth(f, w)                  CGRectMake(RectX(f), RectY(f), w, RectHeight(f))
#define RectSetHeight(f, h)                 CGRectMake(RectX(f), RectY(f), RectWidth(f), h)
#define RectSetX(f, x)                      CGRectMake(x, RectY(f), RectWidth(f), RectHeight(f))
#define RectSetY(f, y)                      CGRectMake(RectX(f), y, RectWidth(f), RectHeight(f))
#define RectSetSize(f, w, h)                CGRectMake(RectX(f), RectY(f), w, h)
#define RectSetOrigin(f, x, y)              CGRectMake(x, y, RectWidth(f), RectHeight(f))
#define RGB(r, g, b)                        [UIColor colorWithRed:(r)/255.0 green:(g)/255.0 blue:(b)/255.0 alpha:1.0]
#define IsRetinaDisplay                     ([[UIScreen mainScreen] respondsToSelector:@selector(scale)] && [[UIScreen mainScreen] scale] == 2.0)
#define KeyboardAnimationDuration           0.25f

//
// Макросы приложения
//

#define APP_STORYBOARD_NAME                 @"Pages"
#define APP_VERSION                         [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleShortVersionString"]

#define ControllerById(cid)                 [[UIStoryboard storyboardWithName:APP_STORYBOARD_NAME bundle:nil] instantiateViewControllerWithIdentifier:cid]
#define NetworkManager                      [ApplicationDelegate networkManager]
#define CurrentUser                         [CurrentUserHelper instance]
#define CurrentIOSVersion                   [DeviceInfoHelper iOSVersion]

//
// Клиент-серверное взаимодействие
//

#define API_SERVER_URL                      @"http://demo.dreamkas.ru/"
#define API_SERVER_PATH                     @"api/1/"

#define API_TEST_SERVER_URL                 @"http://ios.staging.api.lighthouse.pro/"
#define API_TEST_SERVER_PATH                @"api/1/"

#define API_USE_TEST_SERVER                 1
#define API_TEST_LOGIN                      @"owner@lighthouse.pro"
#define API_TEST_PWD                        @"lighthouse"

#endif

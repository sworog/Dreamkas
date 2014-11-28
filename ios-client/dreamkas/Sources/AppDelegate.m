//
//  AppDelegate.m
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "AppDelegate.h"

@implementation AppDelegate

- (id)init
{
    if ((self = [super init])) {
        // инициализация локального хранилища данных
        [MagicalRecord setupAutoMigratingCoreDataStack];
        
        // инициализация модуля для работы с веб-сервисом
        #if API_USE_TEST_SERVER
            self.networkManager = [[RESTClient alloc] initWithBaseURL:[NSURL URLWithString:API_TEST_SERVER_URL]];
        #else
            self.networkManager = [[RESTClient alloc] initWithBaseURL:[NSURL URLWithString:API_SERVER_URL]];
        #endif
    }
    
    return self;
}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    // Override point for customization after application launch.
    
    [[UIApplication sharedApplication]setStatusBarHidden:YES];
    [self configureTapGestureRecognizer];
    
    return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application {
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application {
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application {
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application {
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

#pragma mark - UIGestureRecognizer Methods

- (void)configureTapGestureRecognizer
{
    self.tapGestureRecognizer = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(performWindowTap:)];
    self.tapGestureRecognizer.numberOfTouchesRequired = 1;
    self.tapGestureRecognizer.cancelsTouchesInView = NO;
    [self.window addGestureRecognizer:self.tapGestureRecognizer];
}

- (void)performWindowTap:(UITapGestureRecognizer*)recognizer
{
    NSNotification *notification = [NSNotification notificationWithName:WindowTapNotificationName
                                                                 object:recognizer userInfo:nil];
    [[NSNotificationCenter defaultCenter] postNotification:notification];
}

@end

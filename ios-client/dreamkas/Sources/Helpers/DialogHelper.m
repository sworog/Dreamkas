//
//  DialogHelper.m
//  dreamkas
//
//  Created by sig on 10.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#import "DialogHelper.h"

@implementation DialogHelper

#pragma mark - Сообщения

+ (UIAlertView*)showRequestError:(NSError *)error
{
    if ([DeviceInfoHelper networkIsReachable] == NO) {
        return [self showError:NSLocalizedString(@"dialog_helper_network_not_reachable", nil)];
    }
    
    if (error) {
        NSString *message = [error userInfo][@"message"];
        NSString *reason = [error userInfo][@"reason"];
        NSMutableString *text = [NSMutableString stringWithFormat:@"%@: %d", NSLocalizedString(@"dialog_helper_alert_title_with_code", nil), [error code]];

        if ([message length]) {
            [text appendFormat:@"\n%@: %@", NSLocalizedString(@"dialog_helper_alert_title_with_message", nil), message];
        }
        if ([reason length]) {
            [text appendFormat:@"\n%@", reason];
        }
        return [self showError:text];
    }
    
    return [self showError:NSLocalizedString(@"dialog_helper_request_error", nil)];
}

+ (UIAlertView*)showTimeoutError
{
    return [self showError:NSLocalizedString(@"dialog_helper_timeout_error", nil)];
}

+ (UIAlertView*)showError:(NSString*)text
{
    return [self showError:text withDelegate:nil];
}

+ (UIAlertView*)showError:(NSString*)text withDelegate:(id<UIAlertViewDelegate>)delegate
{
    UIAlertView *alert_view = [[UIAlertView alloc] initWithTitle:NSLocalizedString(@"dialog_helper_alert_title", nil)
                                                         message:text
                                                        delegate:delegate
                                               cancelButtonTitle:NSLocalizedString(@"dialog_helper_alert_ok_button", nil)
                                               otherButtonTitles:nil];
    [alert_view setAccessibilityLabel:AI_Common_ErrorAlert];
	[alert_view show];
    
    return alert_view;
}

+ (UIAlertView*)showMessage:(NSString*)text
{
    return [self showMessage:text withDelegate:nil];
}

+ (UIAlertView*)showMessage:(NSString*)text withDelegate:(id<UIAlertViewDelegate>)delegate
{
    return [self showMessage:text withTitle:nil delegate:delegate];
}

+ (UIAlertView*)showMessage:(NSString*)text withTitle:(NSString*)title
{
    return [self showMessage:text withTitle:title delegate:nil];
}

+ (UIAlertView*)showMessage:(NSString*)text withTitle:(NSString*)title delegate:(id<UIAlertViewDelegate>)delegate
{
    UIAlertView *alert_view = [[UIAlertView alloc] initWithTitle:title
                                                         message:text
                                                        delegate:delegate
                                               cancelButtonTitle:NSLocalizedString(@"dialog_helper_alert_ok_button", nil)
                                               otherButtonTitles:nil];
    [alert_view setAccessibilityLabel:AI_Common_MessageAlert];
	[alert_view show];
    
    return alert_view;
}

+ (UIAlertView*)showConfirmMessage:(NSString*)text delegate:(id<UIAlertViewDelegate>)delegate
{
    return [self showConfirmMessage:text withTitle:nil delegate:delegate];
}

+ (UIAlertView*)showConfirmMessage:(NSString *)text withTitle:(NSString *)title delegate:(id<UIAlertViewDelegate>)delegate
{
    UIAlertView *alert_view = [[UIAlertView alloc] initWithTitle:title
                                                         message:text
                                                        delegate:delegate
                                               cancelButtonTitle:NSLocalizedString(@"dialog_helper_alert_no_button", nil)
                                               otherButtonTitles:NSLocalizedString(@"dialog_helper_alert_yes_button", nil), nil];
    [alert_view setAccessibilityLabel:AI_Common_ConfirmAlert];
	[alert_view show];
    
    return alert_view;
}

@end

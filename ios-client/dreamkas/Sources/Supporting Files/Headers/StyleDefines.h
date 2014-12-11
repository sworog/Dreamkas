//
//  StyleDefines.h
//  dreamkas
//
//  Created by sig on 02.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#ifndef dreamkas_StyleDefines_h
#define dreamkas_StyleDefines_h

//
// Шрифты приложения
//

#define DefaultFontName                     @"Roboto-Regular"
#define DefaultBoldFontName                 @"Roboto-Bold"
#define DefaultLightFontName                @"Roboto-Light"
#define DefaultMediumFontName               @"Roboto-Medium"

#define DefaultFont(fsize)                  [UIFont fontWithName:(DefaultFontName) size:(fsize)]
#define DefaultBoldFont(fsize)              [UIFont fontWithName:(DefaultBoldFontName) size:(fsize)]
#define DefaultLightFont(fsize)             [UIFont fontWithName:(DefaultLightFontName) size:(fsize)]
#define DefaultMediumFont(fsize)            [UIFont fontWithName:(DefaultMediumFontName) size:(fsize)]

//
// Символьные шрифты приложения
//

#define DefaultAwesomeFontName              @"FontAwesome"
#define DefaultAwesomeFont(fsize)           [UIFont fontWithName:(DefaultAwesomeFontName) size:(fsize)]

//
// Цвета приложения
//

#define DefaultCyanColor                    RGB(66, 173, 248)
#define DefaultLightCyanColor               RGB(79, 182, 248)
#define DefaultSuperLightCyanColor          RGB(146, 213, 251)

#define DefaultDarkGrayColor                RGB(105, 105, 105)
#define DefaultGrayColor                    RGB(161, 161, 161)
#define DefaultPreLightGrayColor            RGB(222, 222, 222)
#define DefaultLightGrayColor               RGB(249, 249, 249)

#define DefaultNavbarLightColor             RGB(235, 238, 240)
#define DefaultNavbarDarkColor              RGB(55, 71, 79)

#define DefaultWhiteColor                   [UIColor whiteColor]
#define DefaultBlackColor                   [UIColor blackColor]
#define DefaultRedColor                     RGB(244, 67, 54)

//
// Размеры приложения
//

#define DefaultButtonHeight                 36.0f
#define DefaultCornerRadius                 2.f

#define DefaultBtnShadowRadius              2.f
#define DefaultBtnShadowOpacity             0.6f

#define DefaultTextFieldHeight              52.f
#define DefaultModalOverlayAlpha            0.5f

#define DefaultSingleLineCellHeight         52.f
#define DefaultCellSeparatorHeight          1.f
#define DefaultVerticalCellInsets           17.f

#define DefaultSidemenuOverlayAlpha         0.5f
#define DefaultSidemenuWidth                256.f
#define DefaultSidemenuHeight               768.f

#define DefaultTopPanelHeight               64.f
#define DefaultBottomPanelHeight            64.f

#define DefaultSideHeight                   704.f
#define DefaultSideContainerViewHeight      660.f
#define DefaultLeftSideWidth                704.f
#define DefaultRightSideWidth               320.f

#endif

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

#define DefaultFont(fsize)                  [UIFont fontWithName:(DefaultFontName) size:(fsize)]
#define DefaultBoldFont(fsize)              [UIFont fontWithName:(DefaultBoldFontName) size:(fsize)]
#define DefaultLightFont(fsize)             [UIFont fontWithName:(DefaultLightFontName) size:(fsize)]

//
// Символьные шрифты приложения
//

#define DefaultLiHeiProFontName             @"LiHei Pro"
#define DefaultAwesomeFontName              @"FontAwesome"

#define DefaultLiHeiProFont(fsize)          [UIFont fontWithName:(DefaultLiHeiProFontName) size:(fsize)]
#define DefaultAwesomeFont(fsize)           [UIFont fontWithName:(DefaultAwesomeFontName) size:(fsize)]

//
// Цвета приложения
//

#define DefaultCyanColor                    RGB(64, 169, 244)
#define DefaultLightCyanColor               RGB(89, 189, 246)

#define DefaultDarkGrayColor                RGB(105, 105, 105)
#define DefaultGrayColor                    RGB(158, 158, 158)
#define DefaultLightGrayColor               RGB(236, 236, 236)

#define DefaultWhiteColor                   [UIColor whiteColor]
#define DefaultBlackColor                   [UIColor blackColor]

//
// Размеры приложения
//

#define DefaultButtonHeight                 52.0f
#define DefaultCornerRadius                 3.f

#define DefaultBtnShadowRadius              2.f
#define DefaultBtnShadowOpacity             0.6f

#define DefaultTextFieldHeight              52.f

#define DefaultSingleLineCellHeight         52.f
#define DefaultCellSeparatorHeight          1.f
#define DefaultVerticalCellInsets           17.f

#define DefaultSidemenuOverlayAlpha         0.6f
#define DefaultSidemenuWidth                256.f
#define DefaultSidemenuHeight               768.f

#define DefaultTopPanelHeight               64.f
#define DefaultBottomPanelHeight            64.f
#define DefaultLeftSideWidth                704.f
#define DefaultRightSideWidth               320.f

#endif

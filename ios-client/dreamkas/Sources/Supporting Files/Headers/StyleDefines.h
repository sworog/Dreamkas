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
// Цвета приложения
//

#define CyanColor                           RGB(64, 169, 244)

//
// Размеры приложения
//

#define CoverSize                           CGSizeMake(224.f, 284.f)

#endif

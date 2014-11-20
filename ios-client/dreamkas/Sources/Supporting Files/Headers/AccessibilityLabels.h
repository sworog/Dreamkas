//
//  AccessibilityLabels.h
//  dreamkas
//
//  Created by sig on 15.10.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

#ifndef dreamkas_AccessibilityLabels_h
#define dreamkas_AccessibilityLabels_h

//
// Формат записи: AI_[Название экрана]_[Название элемента]
//

//
// Общие элементы
//

#define AI_Common_ErrorAlert                @"AI_Common_ErrorAlert"
#define AI_Common_MessageAlert              @"AI_Common_MessageAlert"
#define AI_Common_ConfirmAlert              @"AI_Common_ConfirmAlert"

#define AI_Common_NavbarBackButton          @"AI_Common_NavbarBackButton"

#define AI_Common_CellAtIndexPath(sid,rid)  [NSString stringWithFormat:@"AI_Common_CellAtIndexPath_s%ld_r%ld", sid, rid]

//
// Экран (разводящий) аутентификации в приложении
//

#define AI_AuthPage_LogInButton             @"AI_AuthPage_LogInButton"
#define AI_AuthPage_SignInButton            @"AI_AuthPage_SignInButton"

//
// Экран авторизации в приложении
//

#define AI_LogInPage_LoginField             @"AI_LogInPage_LoginField"
#define AI_LogInPage_PwdField               @"AI_LogInPage_PwdField"
#define AI_LogInPage_LogInButton            @"AI_LogInPage_LogInButton"
#define AI_LogInPage_CloseButton            @"AI_LogInPage_CloseButton"

//
// Экран регистрации в приложении
//

#define AI_SignInPage_CloseButton           @"AI_SignInPage_CloseButton"

//
// Экран входа в приложение по пин-коду
//

#define AI_PincodePage_GoAheadButton        @"AI_PincodePage_GoAheadButton"

//
// Экран кассы
//

#define AI_TicketWindowPage_SidemenuButton  @"AI_TicketWindowPage_SidemenuButton"
#define AI_TicketWindowPage_LeftContainer   @"AI_TicketWindowPage_LeftContainer"
#define AI_TicketWindowPage_RightContainer  @"AI_TicketWindowPage_RightContainer"

#define AI_TicketWindowPage_SearchButton    @"AI_TicketWindowPage_SearchButton"

//
// Боковое меню
//

#define AI_Sidemenu_ChangeStoreButton       @"AI_Sidemenu_ChangeStoreButton"

//
// Экран выбора магазина
//

#define AI_SelectStorePage_Table            @"AI_SelectStorePage_Table"

//
// Экран поиска по наименованию, штрих-коду или SKU товара
//

#define AI_SearchPage_SearchField           @"AI_SearchPage_SearchField"

#endif

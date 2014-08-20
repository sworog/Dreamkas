Meta:
@sprint_40
@us_103

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять поступления товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Операции по дате выходит из фильтра дат на странице товародвижения

Meta:
@smoke
@id_s40u103filterDates1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/writeOff/aPreconditionToUserCreation.story,
              precondition/writeOff/aPreconditionToTestDataCreation.story,
              precondition/writeOff/aPreconditionForWriteOffEditionScenario.story,
              precondition/writeOff/aPreconditionToTestWriteOffCreation.story,
              precondition/writeOff/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u103@lighthouse.pro' и пароль 'lighthouse'

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateTo | 27.07.2014 |
| dateFrom | 01.07.2014 |
And пользователь нажимает на кнопку Применить фильтры на странице товародвижения

Then пользователь проверяет, что на странице присутствует текст 'Не найдено ни одной операции с товарами.'

Scenario: Операции по дате входит из фильтра дат на странице товародвижения

Meta:
@smoke
@id_s40u103filterDates2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/writeOff/aPreconditionToUserCreation.story,
              precondition/writeOff/aPreconditionToTestDataCreation.story,
              precondition/writeOff/aPreconditionForWriteOffEditionScenario.story,
              precondition/writeOff/aPreconditionToTestWriteOffCreation.story,
              precondition/writeOff/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u103@lighthouse.pro' и пароль 'lighthouse'

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateTo | 20.08.2014 |
| dateFrom | 29.07.2014 |
And пользователь нажимает на кнопку Применить фильтры на странице товародвижения

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 19.08.2014  | Списание | | Из s40u103-store | 23,98 |

Scenario: Поля Фильтра дат равны дате операции на странице товародвижения

Meta:
@smoke
@id_s40u103filterDates3

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/writeOff/aPreconditionToUserCreation.story,
              precondition/writeOff/aPreconditionToTestDataCreation.story,
              precondition/writeOff/aPreconditionForWriteOffEditionScenario.story,
              precondition/writeOff/aPreconditionToTestWriteOffCreation.story,
              precondition/writeOff/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u103@lighthouse.pro' и пароль 'lighthouse'

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateTo | 19.08.2014 |
| dateFrom | 19.08.2014 |
And пользователь нажимает на кнопку Применить фильтры на странице товародвижения

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 19.08.2014  | Списание | | Из s40u103-store | 23,98 |
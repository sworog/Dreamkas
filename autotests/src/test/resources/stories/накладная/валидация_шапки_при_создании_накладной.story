Meta:
@sprint_40
@us_102

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять поступления товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Валидация обязательного поля магазин при создании накладной

Meta:
@id

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика
And пользователь вводит данные в модальном окне создания накладной
| elementName | value |
| date | 08.11.2014 |
| supplier | s39u102-supplier |
| product.name | s39u102-product1 |
| priceEntered | 150 |
| quantity | 5 |
And пользователь нажимает на кнопку добавления нового товара в накладную
And пользователь нажимает на кнопку Принять, чтобы принять накладную с товарами
Then пользователь проверяет, что у поля с именем 'store' имеется сообщения об ошибке с сообщением 'Заполните это поле' в модальном окне создания накладной

Scenario: Валидация поле поставщик не обязательное при создании накладной

Meta:
@id

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика
And пользователь вводит данные в модальном окне создания накладной
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store |
| product.name | s39u102-product1 |
| priceEntered | 150 |
| quantity | 5 |
And пользователь нажимает на кнопку добавления нового товара в накладную
And пользователь нажимает на кнопку Принять, чтобы принять накладную с товарами
Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / не оплачена | В s39u102-store | 750,00 |

Scenario: Валидация поле дата при создании накладной - пустое значение

Meta:
@id

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика
And пользователь вводит данные в модальном окне создания накладной
| elementName | value |
| date | |
| store | s39u102-store |
| product.name | s39u102-product1 |
| priceEntered | 150 |
| quantity | 5 |
And пользователь нажимает на кнопку добавления нового товара в накладную
And пользователь нажимает на кнопку Принять, чтобы принять накладную с товарами
Then пользователь проверяет, что у поля с именем 'date' имеется сообщения об ошибке с сообщением 'Заполните это поле' в модальном окне создания накладной

Scenario: Валидация поле дата при создании накладной - неверное значение

Meta:
@id

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика
And пользователь вводит данные в модальном окне создания накладной
| elementName | value |
| date | 18.08.99991 |
| store | s39u102-store |
| product.name | s39u102-product1 |
| priceEntered | 150 |
| quantity | 5 |
And пользователь нажимает на кнопку добавления нового товара в накладную
And пользователь нажимает на кнопку Принять, чтобы принять накладную с товарами
Then пользователь проверяет, что у поля с именем 'date' имеется сообщения об ошибке с сообщением 'Вы ввели неверную дату 18.08.99991, формат должен быть следующий дд.мм.гггг чч:мм' в модальном окне создания накладной
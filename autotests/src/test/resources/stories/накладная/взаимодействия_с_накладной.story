Meta:
@sprint_39
@us_102

Narrative:
Как владелец,
Я хочу
Чтобы

Scenario: Создание накладной

Meta:
@smoke
@id_s39u102s1

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
| supplier | s39u102-supplier |
| product.name | s39u102-product1 |
| priceEntered | 150 |
| quantity | 5 |
And пользователь нажимает на кнопку добавления нового товара в накладную
And пользователь нажимает на галочку Оплачено

Then пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь проверяет, что сумма итого равна '750,00' в модальном окне создания накладной

When пользователь нажимает на кнопку Принять, чтобы принять накладную с товарами

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / оплачена | В s39u102-store | 750,00 |

When пользователь нажимает на накладную с номером '10001' на странице товародвижения

Then пользователь проверяет поля в модальном окне редактирования накладной
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store |
| supplier | s39u102-supplier |
And пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь проверяет, что сумма итого равна '750,00' в модальном окне редактирования накладной

Scenario: Редактирование накладной

Meta:
@smoke
@id_s39u102s2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story,
              precondition/sprint-39/us-102/aPreconditionForInvoiceEditionScenario.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения
And пользователь вводит данные в модальном окне редактирования накладной
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store1 |
| supplier | s39u102-supplier1 |
| product.name | s39u102-product2 |
And пользователь нажимает на кнопку добавления нового товара в накладную в модальном окне редактирования накладной

Then пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '875,50' в модальном окне редактирования накладной

When пользователь нажимает на кнопку сохранения накладной в модальном окне редактирования накладной

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / не оплачена | В s39u102-store1 | 875,50 |

When пользователь нажимает на накладную с номером '10001' на странице товародвижения

Then пользователь проверяет поля в модальном окне редактирования накладной
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store1 |
| supplier | s39u102-supplier1 |
Then пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '875,50' в модальном окне редактирования накладной

Scenario: Удаление накладной

Meta:
@smoke
@id_s39u102s3

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения
And пользователь нажимает на кнопку удаления накладной в модальном окне редактирования накладной
And пользователь подтверждает удаление накладной в модальном окне редактирования накладной

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет, что в операциях товародвижения отсутствует последняя созданная накладная

Scenario: Навигацию на страницу товародвижения через меню

Meta:
@smoke
@id_s39u102s4

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given пользователь открывает стартовую страницу авторизации
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на пункт меню Товароджижение в боковом меню навигации

Then пользователь проверяет, что заголовок страницы товародвижения равен 'Товародвижение'

Scenario: Проверка заголовка модального окна создания накладной

Meta:
@id_s39u102s5

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика

Then пользователь проверяет, что заголовок модального окна создания накладной равен 'Приёмка товаров от поставщика'


Scenario: Проверка заголовка модального окна редактирования накладной

Meta:
@id_s39u102s6


GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения

Then пользователь проверяет, что заголовок модального окна редактирования накладной равен 'Редактирование приёмки товаров от поставщика'

Scenario: Проверка сообщения при отсутствии операций по товародвижению

Meta:
@id_s39u102s7

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет, что на странице присутствует текст 'Не найдено ни одной операции с товарами.'

Scenario: Поле дата выставляется автоматически при создании накладной и равно сегодняшней дате

Meta:
@id_s39u102s8
@smoke

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика

Then пользователь проверяет, что поле дата заполнено сегодняшней датой

Scenario: Цена продажи подставляется автоматически при добаления продукта через автокомплит, если у этого продукта заполнено это поле

Meta:
@smoke
@id_s39u102s9

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Принять от поставщика
And пользователь вводит данные в модальном окне создания накладной
| elementName | value |
| product.name | s39u102-product1 |

Then пользователь проверяет, что поле с именем 'priceEntered' заполнено значением '100,00' в модальном окне создания накладной

Scenario: Проверка сменя галочки Оплачено у накладной

Meta:
@id_s39u102s10
@smoke

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет операции на странице товародвижения
| date | type | status | store | sumTotal |
| 28.07.2014 | Приёмка | / не оплачена | В s39u102-store | 750,00 |

When пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения
And пользователь нажимает на галочку Оплачено в модальном окне редактирования накладной
And пользователь нажимает на кнопку сохранения накладной в модальном окне редактирования накладной

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | status | store | sumTotal |
| 28.07.2014 | Приёмка | / оплачена | В s39u102-store | 750,00 |

Scenario: Удаление продукта в накладной

Meta:
@smoke
@id_s39u102s12

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story,
              precondition/sprint-39/us-102/aPreconditionForInvoiceEditionScenario.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения
And пользователь вводит данные в модальном окне редактирования накладной
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store1 |
| supplier | s39u102-supplier1 |
| product.name | s39u102-product2 |
And пользователь нажимает на кнопку добавления нового товара в накладную в модальном окне редактирования накладной

Then пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '875,50' в модальном окне редактирования накладной

When пользователь удаляет товар с названием 's39u102-product1' в модальном окне редактирования накладной

Then пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |

When пользователь нажимает на кнопку сохранения накладной в модальном окне редактирования накладной

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / не оплачена | В s39u102-store1 | 125,50 |

When пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения

Then пользователь проверяет, что список товаров содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |

Scenario: Invoice creation with supplier create

Meta:
@id
@smoke
@test

Scenario: Invoice creation with product create

Meta:
@id
@smoke
@test

Scenario: Invoice creation with supplier and product create

Meta:
@id
@smoke
@test

Scenario: Invoice edition with supplier create

Meta:
@id
@smoke
@test

Scenario: Invoice edition with product create

Meta:
@id
@smoke
@test

Scenario: Invoice edition with supplier and product create

Meta:
@id
@smoke
@test

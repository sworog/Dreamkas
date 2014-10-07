Meta:
@sprint_40
@us_104

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять оприходования товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Создание оприходования

Meta:
@smoke
@id_s40u104s1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения

When пользователь* находится в модальном окне 'создания оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u104-store |
| product.name | s40u104-product1 |
| price | 150 |
| quantity | 5 |
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь* проверяет, что сумма итого равна '750,00'

When пользователь* нажимает на кнопку создания 'Оприходовать'

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | sumTotal |
| 01.10.2014 | Оприходование | В s40u104-store | 750,00 |

When пользователь нажимает на оприходование с номером '10001' на странице товародвижения

When пользователь* находится в модальном окне 'редактирования оприходования'

Then пользователь* в модальном окне проверяет поля
| elementName | value |
| date | 01.10.2014 |
| store | s40u104-store |
And пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь* проверяет, что сумма итого равна '750,00'

Scenario: Редактирование оприходования

Meta:
@smoke
@id_s40u104s2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionForStockInEditionScenario.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходование с помощью конструктора оприходований на странице товародвижения
And пользователь* находится в модальном окне 'редактирования оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u104-store1 |
| product.name | s40u104-product2 |
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 11,99  | 2,0 шт. | 23,98 |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '149,48'

When пользователь* в модальном окне нажимает на кнопку сохранения

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | sumTotal |
| 01.10.2014 | Оприходование | В s40u104-store1 | 149,48 |

When пользователь нажимает на оприходование с номером '10001' на странице товародвижения

When пользователь* находится в модальном окне 'редактирования оприходования'

Then пользователь* в модальном окне проверяет поля
| elementName | value |
| date | 01.10.2014 |
| store | s40u104-store1 |
Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 11,99  | 2,0 шт. | 23,98 |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '149,48'

Scenario: Удаление оприходования

Meta:
@smoke
@id_s40u104s3

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходование с помощью конструктора оприходований на странице товародвижения
And пользователь* в модальном окне нажимает на кнопку удаления
And пользователь* в модальном окне подтверждает удаление

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет, что в операциях товародвижения отсутствует последнее созданное оприходование

Scenario: Проверка заголовка модального окна создания оприходования

Meta:
@id_s40u104s4

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'

Then пользователь* в модальном окне проверяет, что заголовок равен 'Оприходование товаров'

Scenario: Проверка заголовка модального окна редактирования оприходования

Meta:
@id_s40u104s5


GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходование с помощью конструктора оприходований на странице товародвижения
And пользователь* находится в модальном окне 'редактирования оприходования'

Then пользователь* в модальном окне проверяет, что заголовок равен 'Редактирование оприходования'

Scenario: Поле дата выставляется автоматически при создании оприходования и равно сегодняшней дате

Meta:
@id_s40u104s6
@smoke

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения

Then пользователь* проверяет, что поле дата товародвижения заполнено сегодняшней датой

Scenario: Удаление продукта в оприходовании

Meta:
@smoke
@id_s40u104s7

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story,
              precondition/stockIn/aPreconditionForStockInEditionScenario.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходование с помощью конструктора оприходований на странице товародвижения
When пользователь* находится в модальном окне 'редактирования оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u104-store1 |
| product.name | s40u104-product2 |
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 11,99  | 2,0 шт. | 23,98 |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '149,48'

When пользователь* в модальном окне товародвижения удаляет товар с названием 's40u104-product1'

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '125,50'

When пользователь* в модальном окне нажимает на кнопку сохранения

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | sumTotal |
| 01.10.2014 | Оприходование | В s40u104-store1 | 125,50 |

When пользователь нажимает на последнее созданное оприходование с помощью конструктора оприходований на странице товародвижения

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '125,50'
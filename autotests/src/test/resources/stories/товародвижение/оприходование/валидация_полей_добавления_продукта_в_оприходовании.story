Meta:
@sprint_40
@us_104

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять списания товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Валидация полей добавления продукта в оприходовании - нельзя создать списание без продукта

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* вводит данные в поля
| elementName | value |
| date | 08.11.2014 |
| store | s40u104-store |

When пользователь* нажимает на кнопку создания 'Списать'

Then пользователь видит сообщение об ошибке 'Нужно добавить минимум один товар'

Scenario: Валидация полей добавления продукта в оприходовании - поле автокомплита обязательно

GivenStories: precondition/writeOff/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* вводит данные в поля
| elementName | value |
| price | 150 |
| quantity | 5 |

When пользователь* нажимает на кнопку добавления нового товара

Then пользователь видит сообщение об ошибке 'Заполните это поле'

Scenario: Валидация полей добавления продукта в оприходовании - поиск несуществующего товара

GivenStories: precondition/writeOff/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* вводит данные в поля
| elementName | value |
| product.name | !не существует такого товара |
| price | 150 |
| quantity | 5 |

When пользователь* нажимает на кнопку добавления нового товара

Then пользователь видит сообщение об ошибке 'Такого товара не существует'

Scenario: Валидация полей добавления продукта в оприходовании - поле кол-во

Given пользователь запускает консольную команду для создания пользователя с параметрами: адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

And пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает группу с именем 's40u104-group'
And пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает продукт с именем 's40u104-product1', еденицами измерения 'шт.', штрихкодом 's40u104barcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 's40u104-group'

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* вводит данные в поля
| elementName | value |
| product.name | s40u104-product1 |
| price | 150 |
And пользователь* вводит значение value в поле с именем 'quantity'

When пользователь* нажимает на кнопку добавления нового товара

Then пользователь видит сообщение об ошибке c текстом errorMessage

Examples:
| value | errorMessage |
|  | Заполните это поле |
| -10 | Значение должно быть больше 0 |
| -1 | Значение должно быть больше 0 |
| -1,12 | Значение должно быть больше 0 |
| -1.12 | Значение должно быть больше 0 |
| -1.123 | Значение должно быть больше 0 |
| -1,1234 | Значение не должно содержать больше 3 цифр после запятой |
| -1,123 | Значение должно быть больше 0 |
| 1,1234 | Значение не должно содержать больше 3 цифр после запятой |
| 1.1234 | Значение не должно содержать больше 3 цифр после запятой |
| 0 | Значение должно быть больше 0 |
| asdd | Значение должно быть числом |
| ADHF | Значение должно быть числом |
| домик | Значение должно быть числом |
| ДОМИЩЕ | Значение должно быть числом |
| ^%#$)& | Значение должно быть числом |

Scenario: Валидация полей добавления продукта в оприходовании - поле цена

Given пользователь запускает консольную команду для создания пользователя с параметрами: адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

And пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает группу с именем 's40u104-group'
And пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает продукт с именем 's40u104-product1', еденицами измерения 'шт.', штрихкодом 's40u104barcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 's40u104-group'

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* вводит данные в поля
| elementName | value |
| product.name | s40u104-product1 |
| quantity | 5 |
And пользователь* вводит значение value в поле с именем 'price'
When пользователь* нажимает на кнопку добавления нового товара

Then пользователь видит сообщение об ошибке c текстом errorMessage

Examples:
| value | errorMessage |
|  | Заполните это поле |
| 12,123 | Цена не должна содержать больше 2 цифр после запятой |
| -1 | Цена не должна быть меньше или равна нулю |
| -1,123 | Цена не должна быть меньше или равна нулю. Цена не должна содержать больше 2 цифр после запятой |
| 0 | Цена не должна быть меньше или равна нулю |
| harry | Значение должно быть числом |
| HARRY | Значение должно быть числом |
| цена | Значение должно быть числом |
| ЦЕНА | Значение должно быть числом |
| @#$#$# | Значение должно быть числом |
| 10000001 | Цена не должна быть больше 10000000 |
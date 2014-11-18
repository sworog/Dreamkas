Meta:
@us_129

Narrative:
При первом использовании Dreamkas,
Я хочу полностью создать оприходование товаров в магазине,
Чтобы не отвлекаться от работы на предварительное заполнение справочников

Scenario: Создание новой группы из создания товара в оприходовании, когда нет групп

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/пользователь/создание_юзера.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* нажимает на елемент с именем 'кнопка 'Создать товар''

When пользователь* находится в модальном окне 'создания товара внутри оприходования'
And пользователь* нажимает на елемент с именем 'кнопка 'Создать группу''

When пользователь* находится в модальном окне 'создания группы внутри создания товара внутри оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| name | Группа у Магазин у дома |
And пользователь* в модальном окне нажимает на кнопку создания

When пользователь* находится в модальном окне 'создания товара внутри оприходования'

Then пользователь* в модальном окне проверяет, что поле с именем 'group' имеет значение 'Группа у Магазин у дома'

Given the user opens catalog page

Then the user asserts the groups list contain group with name 'Группа у Магазин у дома'

Scenario: Создание новой группы из создания товара в оприходовании, когда группы есть

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/пользователь/создание_юзера.story

Given пользователь с адресом электронной почты 'user@lighthouse.pro' создает группу с именем 'user-group1'

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* нажимает на елемент с именем 'кнопка 'Создать товар''

When пользователь* находится в модальном окне 'создания товара внутри оприходования'
And пользователь* нажимает на елемент с именем 'плюсик, чтобы создать новую группу'

When пользователь* находится в модальном окне 'создания группы внутри создания товара внутри оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| name | Группа у Магазин у дома |
And пользователь* в модальном окне нажимает на кнопку создания

When пользователь* находится в модальном окне 'создания товара внутри оприходования'

Then пользователь* в модальном окне проверяет, что поле с именем 'group' имеет значение 'Группа у Магазин у дома'

Given the user opens catalog page

Then the user asserts the groups list contain group with name 'Группа у Магазин у дома'

Scenario: Создание нового товара и группы из создания товара в оприходовании, когда товаров нет

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/пользователь/создание_юзера.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* нажимает на елемент с именем 'кнопка 'Создать товар''

When пользователь* находится в модальном окне 'создания товара внутри оприходования'
And пользователь* нажимает на елемент с именем 'кнопка 'Создать группу''

When пользователь* находится в модальном окне 'создания группы внутри создания товара внутри оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| name | Группа у Магазин у дома |
And пользователь* в модальном окне нажимает на кнопку создания

When пользователь* находится в модальном окне 'создания товара внутри оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| name | Продукт1 |
| unit | шт |
| barcode | 12345678910 |
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |
And пользователь* в модальном окне нажимает на кнопку создания

When пользователь* находится в модальном окне 'создания оприходования'

Then пользователь* в модальном окне проверяет, что поле с именем 'product.name' имеет значение 'Продукт1'
Then пользователь проверяет, что у элемента с именем 'product.name' css 'background-image' имеет значение 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAC9QufkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJxJREFUeNpiZiAByMhICfDx8VYA8YVPnz7/YCRFI5DaD8QGQHwBiB2ZydAIAhIgzISkwIBIjTCwkQmqYD6QOg+kz0MVE9KY+OTJsw2MQAUJQM58JAmwf6BsXBoXgBgsWFxqANXEgE8jCDADg/wCMOgV0BRKQDFOjWDNIAJowEYsBuDVCNdMwACsGlE04zAAp0Z8iSIBiB0IqQMIMABA/EDDnh+dTQAAAABJRU5ErkJggg==)'

Given the user opens catalog page

When the user clicks on the group with name 'Группа у Магазин у дома'

Then пользователь проверяет, что список продуктов содержит продукты с данными
| name | sellingPrice | barcode |
| Продукт1 | 123,56 | 12345678910|

When пользователь нажимает на товар с названием 'Продукт1'

Then пользователь проверяет заполненные поля в модальном окне редактирования товара
| elementName | value |
| name | Продукт1 |
| unit | шт |
| barcode | 12345678910 |
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

Scenario: Создание нового товара из создания товара в оприходовании, когда товары и группа есть

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/пользователь/создание_юзера.story,
              precondition/магазин/создание_товара.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* нажимает на елемент с именем 'плюсик, чтобы создать новый товар'

When пользователь* находится в модальном окне 'создания товара внутри оприходования'
And пользователь* нажимает на елемент с именем 'плюсик, чтобы создать новую группу'

When пользователь* находится в модальном окне 'создания группы внутри создания товара внутри оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| name | Группа у Магазин у дома |
And пользователь* в модальном окне нажимает на кнопку создания

When пользователь* находится в модальном окне 'создания товара внутри оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| name | Продукт1 |
| unit | шт |
| barcode | 12345678910 |
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |
And пользователь* в модальном окне нажимает на кнопку создания

When пользователь* находится в модальном окне 'создания оприходования'

Then пользователь* в модальном окне проверяет, что поле с именем 'product.name' имеет значение 'Продукт1'
Then пользователь проверяет, что у элемента с именем 'product.name' css 'background-image' имеет значение 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAC9QufkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJxJREFUeNpiZiAByMhICfDx8VYA8YVPnz7/YCRFI5DaD8QGQHwBiB2ZydAIAhIgzISkwIBIjTCwkQmqYD6QOg+kz0MVE9KY+OTJsw2MQAUJQM58JAmwf6BsXBoXgBgsWFxqANXEgE8jCDADg/wCMOgV0BRKQDFOjWDNIAJowEYsBuDVCNdMwACsGlE04zAAp0Z8iSIBiB0IqQMIMABA/EDDnh+dTQAAAABJRU5ErkJggg==)'

Given the user opens catalog page

When the user clicks on the group with name 'Группа у Магазин у дома'

Then пользователь проверяет, что список продуктов содержит продукты с данными
| name | sellingPrice | barcode |
| Продукт1 | 123,56 | 12345678910|

When пользователь нажимает на товар с названием 'Продукт1'

Then пользователь проверяет заполненные поля в модальном окне редактирования товара
| elementName | value |
| name | Продукт1 |
| unit | шт |
| barcode | 12345678910 |
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

Scenario: Подсветка поля после выбора товара в модальном окне оприходования

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/пользователь/создание_юзера.story,
              precondition/магазин/создание_товара.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходовать на странице товародвижения
And пользователь* находится в модальном окне 'создания оприходования'
And пользователь* в модальном окне вводит данные
| elementName | value |
| product.name | user-product1 |

Then пользователь проверяет, что у элемента с именем 'product.name' css 'background-image' имеет значение 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAC9QufkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJxJREFUeNpiZiAByMhICfDx8VYA8YVPnz7/YCRFI5DaD8QGQHwBiB2ZydAIAhIgzISkwIBIjTCwkQmqYD6QOg+kz0MVE9KY+OTJsw2MQAUJQM58JAmwf6BsXBoXgBgsWFxqANXEgE8jCDADg/wCMOgV0BRKQDFOjWDNIAJowEYsBuDVCNdMwACsGlE04zAAp0Z8iSIBiB0IqQMIMABA/EDDnh+dTQAAAABJRU5ErkJggg==)'
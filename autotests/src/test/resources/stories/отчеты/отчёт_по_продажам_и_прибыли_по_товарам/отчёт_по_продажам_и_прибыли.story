Meta:
@release_0.44
@us_118.1

Narrative:
Как владелец,
Я хочу просматривать отчёт о продажах и прибыли по товарам внутри группы
Чтобы понимать, какие товары наиболее эффективны

Scenario: Проверка правильности отчёта по продажам и прибыли, когда продаж не было

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story


Given пользователь открывает страницу отчета по продажам и прибыли
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'
And пользователь кликает на группу 'reports-group1'

When пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'

Then пользователь* проверяет, что список 'отчета по продажам и прибыли по товарам внутри группы' содержит точные данные
| товар | продажи | себестоимость | прибыль | количество |
| reports-product1 | 0,00 (0%) | 0,00 | 0,00 (0%) | 0,0 шт. |

Scenario: Проверка правильности отчёта по продажам и прибыли по товарам в группе после совершения продажи при отсутствии товаров

Meta:
@smoke

GivenStories:   precondition/customPrecondition/symfonyEnvInitPrecondition.story,
                precondition/отчеты/создание_юзера.story,
                precondition/отчеты/создание_магазина_с_товаром.story

Given пользователь создает чек c датой 'saleTodayDate-7'
And пользователь добавляет товар в чек с именем 'reports-product1', количеством '2' и по цене '250'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 'reports@lighthouse.pro' в магазине с именем 'store-reports' совершает продажу по созданному чеку

Given пользователь запускает команду пересчета метрики продуктов

Given пользователь открывает страницу отчета по продажам и прибыли
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'
And пользователь кликает на группу 'reports-group1'

Then пользователь* проверяет, что список 'отчета по продажам и прибыли по товарам внутри группы' содержит точные данные
| товар | продажи | себестоимость | прибыль | количество |
| reports-product1 | 500,00 (100%) | 200,00 | 300,00 (100%) | 2,0 шт. |

Scenario: Проверка правильности отчёта по продажам и прибыли по товарам в группе, если нет цены закупки у товара

Meta:
@smoke

GivenStories:   precondition/customPrecondition/symfonyEnvInitPrecondition.story,
                precondition/отчеты/создание_юзера.story,
                precondition/отчеты/создание_магазина_с_товаром.story

Given пользователь с адресом электронной почты 'reports@lighthouse.pro' создает группу с именем 'reports-group2'
And пользователь с адресом электронной почты 'reports@lighthouse.pro' создает продукт с именем 'reports-product2', еденицами измерения 'шт.', штрихкодом 'reports-barcode-2', НДС '0', ценой закупки '' и ценой продажи '110' в группе с именем 'reports-group2'

Given пользователь создает чек c датой 'saleTodayDate-7'
And пользователь добавляет товар в чек с именем 'reports-product2', количеством '2' и по цене '250'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 'reports@lighthouse.pro' в магазине с именем 'store-reports' совершает продажу по созданному чеку

Given пользователь запускает команду пересчета метрики продуктов

Given пользователь открывает страницу отчета по продажам и прибыли
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'
And пользователь кликает на группу 'reports-group2'

Then пользователь* проверяет, что список 'отчета по продажам и прибыли по товарам внутри группы' содержит точные данные
| товар | продажи | себестоимость | прибыль | количество |
| reports-product2 | 500,00 (100%) | 0,00 | 500,00 (100%) | 2,0 шт. |

Scenario: Проверка правильности отчёта по продажам и прибыли по товарам в группе при пересортице

Meta:
@smoke

GivenStories:   precondition/customPrecondition/symfonyEnvInitPrecondition.story,
                precondition/отчеты/создание_юзера.story,
                precondition/отчеты/создание_магазина_с_товаром.story,
                precondition/отчеты/создание_поставщика.story

Given пользователь создает апи объект накладной с датой '28.07.2014', статусом Оплачено 'false', магазином с именем 'store-reports', поставщиком с именем 'reports-supplier'
And пользователь добавляет продукт с именем 'reports-product1', ценой '200' и количеством '5' к апи объекту накладной
And пользователь с адресом электронной почты 'reports@lighthouse.pro' создает накладную через конструктор накладных

Given пользователь создает чек c датой 'saleTodayDate-7'
And пользователь добавляет товар в чек с именем 'reports-product1', количеством '6' и по цене '250'
And пользователь вносит наличные в размере '1500' рублей
And пользователь с адресом электронной почты 'reports@lighthouse.pro' в магазине с именем 'store-reports' совершает продажу по созданному чеку

Given пользователь запускает команду пересчета метрики продуктов

Given пользователь открывает страницу отчета по продажам и прибыли
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'
And пользователь кликает на группу 'reports-group1'

Then пользователь* проверяет, что список 'отчета по продажам и прибыли по товарам внутри группы' содержит точные данные
| товар | продажи | себестоимость | прибыль | количество |
| reports-product1 | 1 500,00 (100%) | 1 200,00 | 300,00 (100%) | 6,0 шт. |

Scenario: Проверка правильности отчёта по продажам и прибыли по товарам в группе при продаже товара по ценам из двух приемок

Meta:
@smoke

GivenStories:   precondition/customPrecondition/symfonyEnvInitPrecondition.story,
                precondition/отчеты/создание_юзера.story,
                precondition/отчеты/создание_магазина_с_товаром.story,
                precondition/отчеты/создание_поставщика.story

Given пользователь создает апи объект накладной с датой '28.07.2014', статусом Оплачено 'false', магазином с именем 'store-reports', поставщиком с именем 'reports-supplier'
And пользователь добавляет продукт с именем 'reports-product1', ценой '200' и количеством '5' к апи объекту накладной
And пользователь с адресом электронной почты 'reports@lighthouse.pro' создает накладную через конструктор накладных
Given пользователь создает апи объект накладной с датой '28.07.2014', статусом Оплачено 'false', магазином с именем 'store-reports', поставщиком с именем 'reports-supplier'
And пользователь добавляет продукт с именем 'reports-product1', ценой '250' и количеством '1' к апи объекту накладной
And пользователь с адресом электронной почты 'reports@lighthouse.pro' создает накладную через конструктор накладных

Given пользователь создает чек c датой 'saleTodayDate-7'
And пользователь добавляет товар в чек с именем 'reports-product1', количеством '6' и по цене '250'
And пользователь вносит наличные в размере '1750' рублей
And пользователь с адресом электронной почты 'reports@lighthouse.pro' в магазине с именем 'store-reports' совершает продажу по созданному чеку

Given пользователь запускает команду пересчета метрики продуктов

Given пользователь открывает страницу отчета по продажам и прибыли
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'
And пользователь кликает на группу 'reports-group1'

Then пользователь* проверяет, что список 'отчета по продажам и прибыли по товарам внутри группы' содержит точные данные
| товар | продажи | себестоимость | прибыль | количество |
| reports-product1 | 1 500,00 (100%) | 1 250,00 | 250,00 (100%) | 6,0 шт. |

Scenario: Проверка заголовка страницы отчёта по продажам и прибыли по товарам в группе

Meta:

GivenStories:   precondition/отчеты/создание_юзера.story,
                precondition/отчеты/создание_магазина_с_товаром.story

Given пользователь открывает стартовую страницу авторизации
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на пункт меню 'Отчеты' в боковом меню навигации
And пользователь нажимает на кнопку отчетов с названием 'Продажи и прибыль по товарам'

Then пользователь ждет пока загрузится страница

When пользователь кликает на группу 'reports-group1'
And пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'

Then пользователь* проверяет, что заголовок равен 'Продажи и прибыль по товарам'
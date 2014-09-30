Meta:
@sprint_43
@us_115.1

Narrative:
Как владелец,
Я хочу просматривать отчет по остаткам для каждого магазина,
Чтобы легче принимать решение о заказе товаров

Scenario: Проверка пополнения остатков при приемке товаров

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_приемки.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 0 дн. | 0 шт. / дн. | 5 шт. |

Scenario: Проверка пополнения остатков при оприходывании

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/остатки_товаров/создание_оприходования.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 0 дн. | 0 шт. / дн. | 3 шт. |

Scenario: Проверка уменьшения остатков при списании

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/остатки_товаров/создание_списания.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 0 дн. | 0 шт. / дн. | -2 шт. |

Scenario: Проверка уменьшения остатков при возврате поставщику

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_возврата.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 0 дн. | 0 шт. / дн. | -10 шт. |

Scenario: Проверка расчета запаса и расхода

Meta:
@smoke
@skip
@ignore

Scenario: Проверка расчета запаса и расхода за 30 дней включительно

Meta:
@smoke
@skip
@ignore

Scenario: Проверка заголовка страницы отчетов

Meta:

GivenStories: precondition/отчеты/создание_юзера.story

Given пользователь открывает стартовую страницу авторизации
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на пункт меню 'Отчеты' в боковом меню навигации
And пользователь* находится на странице 'странице отчетов'

Then пользователь* проверяет, что заголовок равен 'Отчеты'

Scenario: Проверка заголовка страницы остатка товаров

Meta:

GivenStories: precondition/отчеты/создание_юзера.story

Given пользователь открывает стартовую страницу авторизации
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на пункт меню 'Отчеты' в боковом меню навигации
And пользователь нажимает на кнопку отчетов с названием 'Остатки товаров'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет, что заголовок равен 'Остатки товаров'
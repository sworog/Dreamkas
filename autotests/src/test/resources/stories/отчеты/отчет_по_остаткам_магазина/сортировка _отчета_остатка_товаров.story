Meta:
@sprint_43
@us_115.1

Narrative:
Как владелец,
Я хочу просматривать отчет по остаткам для каждого магазина,
Чтобы легче принимать решение о заказе товаров

Scenario: Проверка, что сортировка отчета остатков по умолчанию выставлена на колонку наименования и сортируется по возрастанию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь проверяет, что у элемента с именем 'колонка 'Наименование'' аттрибут 'data-sorted-direction' имеет значение 'ascending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |

Scenario: Сортировка отчета остатков по наименованию и убыванию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Наименование''

Then пользователь проверяет, что у элемента с именем 'колонка 'Наименование'' аттрибут 'data-sorted-direction' имеет значение 'descending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |

Scenario: Сортировка отчета остатков по запасу и возрастанию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Запас''

Then пользователь проверяет, что у элемента с именем 'колонка 'Запас'' аттрибут 'data-sorted-direction' имеет значение 'ascending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |

Scenario: Сортировка отчета остатков по запасу и убыванию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Запас''
And пользователь* нажимает на елемент с именем 'колонка 'Запас''

Then пользователь проверяет, что у элемента с именем 'колонка 'Запас'' аттрибут 'data-sorted-direction' имеет значение 'descending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |

Scenario: Сортировка отчета остатков по расходу и возрастанию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Расход''

Then пользователь проверяет, что у элемента с именем 'колонка 'Расход'' аттрибут 'data-sorted-direction' имеет значение 'ascending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |

Scenario: Сортировка отчета остатков по расходу и убыванию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Расход''
And пользователь* нажимает на елемент с именем 'колонка 'Расход''

Then пользователь проверяет, что у элемента с именем 'колонка 'Расход'' аттрибут 'data-sorted-direction' имеет значение 'descending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |

Scenario: Сортировка отчета остатков по остатку и возрастанию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Остаток''

Then пользователь проверяет, что у элемента с именем 'колонка 'Остаток'' аттрибут 'data-sorted-direction' имеет значение 'ascending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |

Scenario: Сортировка отчета остатков по остатку и убыванию

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story,
              precondition/отчеты/создание_поставщика.story,
              precondition/отчеты/остатки_товаров/создание_операций_для_тестирования_сортировки_остатков.story

Given пользователь открывает страницу отчета остатка товаров магазина с названием 'store-reports'
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится на странице 'странице отчета остатка товаров'

When пользователь* нажимает на елемент с именем 'колонка 'Остаток''
And пользователь* нажимает на елемент с именем 'колонка 'Остаток''

Then пользователь проверяет, что у элемента с именем 'колонка 'Остаток'' аттрибут 'data-sorted-direction' имеет значение 'descending'

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 300 дн. | 0,03 шт. / дн. | 9,0 шт. |
| reports-product3 | reports-barcode-3 | 30,4 дн. | 0,23 шт. / дн. | 7,0 шт. |
| reports-product2 | reports-barcode-2 | 15,2 дн. | 0,33 шт. / дн. | 5,0 шт. |
| reports-product4 | reports-barcode-4 | 17,6 дн. | 0,17 шт. / дн. | 3,0 шт. |
| reports-product5 | reports-barcode-5 | 2,2 дн. | 0,93 шт. / дн. | 2,0 шт. |
8.3 Добавление данных о товаре в накладную без редактирования

Narrative:
Как заведующий отделом,
Я хочу добавить в накладную данные о принятых товарах,
Чтобы зафиксировать в системе факт прихода товара

Scenario: Adding invoice products: 1 product with name autocomplete
!-- Создаем тестовый продукт с именем 'Тестовое имя 25-3'
Given there is the product with 'Тестовое имя 25-3' name, 'SKU-AIP1PWNAU' sku, 'BARCode-AIP1PWNAU' barcode
!--Заполняем шапку накладной
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
!--Переходим к добавлению товаров
And the user navigates to invoice product addition
!--Автокомплитим
And the user inputs 'Тестовое имя 25-3' in the invoice product 'name' field
!--Проверяем автокомплит
Then the user checks invoice elements values
| elementName | Тестовое имя 25-3 |
| productSku | ? |
| productBarCode | ? |
!--Вводим доп данные
When the user inputs '5' in the invoice product 'productAmount' field
And the user inputs '5' in the invoice product 'invoiceCost' field
!--Нажимаем кнопку создать новый продукт
And the user clicks the add more product button
!--Чекаем введенные значения
Then the user checks invoice elements values
| elementName | Тестовое имя 25-3 |
| productSku | ? |
| productBarCode | ? |
| productAmount | ? |
| invoiceCost | ? |
| Total | Итого: 20 позиций на сумму 231312.34 рублей |
!--Создаем и чекаем алерт ошибку
When the user clicks the invoice create button

Scenario: Adding invoice products: 1 product with sku autocomplete
!-- Adding test product

Scenario: Adding invoice products: 1 product with barcode autocomplete
!-- Adding test product

Scenario: Adding invoice products: 1 product with name autocomplete validation 0 symbols
!--Валидация автокомплита: 0 символов - нет автокомплита
!-- Adding test product
Scenario: Adding invoice products: 1 product with name autocomplete validation 2 symbols
!--Валидация автокомплита: 2 символов - нет автокомплита
!-- Adding test product

Scenario: Adding invoice products: 1 product with sku autocomplete validation 0 symbols
!--Валидация автокомплита: 0 символов - нет автокомплита
!-- Adding test product
Scenario: Adding invoice products: 1 product with sku autocomplete validation 2 symbols
!--Валидация автокомплита: 2 символов - нет автокомплита
!-- Adding test product

Scenario: Adding invoice products: 1 product with barcode autocomplete validation 0 symbols
!--Валидация автокомплита: 0 символов - нет автокомплита
!-- Adding test product
Scenario: Adding invoice products: 1 product with barcode autocomplete validation 2 symbols
!--Валидация автокомплита: 2 символов - нет автокомплита
!-- Adding test product


!--Валидация различных значений поиска
Scenario: Adding invoice products: 1 product with name autocomplete validation rus search
!-- Adding test product
Scenario: Adding invoice products: 1 product with name autocomplete validation numbers search
!-- Adding test product
Scenario: Adding invoice products: 1 product with name autocomplete validation eng search
!-- Adding test product
Scenario: Adding invoice products: 1 product with name autocomplete validation symbols search
!-- Adding test product

!--Валидация различных значений поиска
Scenario: Adding invoice products: 1 product with sku autocomplete validation rus search
!-- Adding test product
Scenario: Adding invoice products: 1 product with sku autocomplete validation numbers search
!-- Adding test product
Scenario: Adding invoice products: 1 product with sku autocomplete validation eng search
!-- Adding test product
Scenario: Adding invoice products: 1 product with sku autocomplete validation symbols search
!-- Adding test product

!--Валидация различных значений поиска
Scenario: Adding invoice products: 1 product with barcode autocomplete validation rus search
!-- Adding test product
Scenario: Adding invoice products: 1 product with barcode autocomplete validation numbers search
!-- Adding test product
Scenario: Adding invoice products: 1 product with barcode autocomplete validation eng search
!-- Adding test product
Scenario: Adding invoice products: 1 product with barcode autocomplete validation symbols search
!-- Adding test product

Scenario: Adding invoice products: 2 product with barcode, name autocomplete
!--Создание накладной с двумя товарами, поиск 1 товару - имя, 2 - штрихкод
!-- Adding test product
!-- Adding test product
!-- Adding test product

Scenario: Adding invoice products: 3 product with barcode, name autocomplete
!--Создание накладной с двумя товарами, поиск 1 товару - имя, 2 - штрихкод, 3 - артикул
!-- Adding test product
!-- Adding test product
!-- Adding test product

Scenario: sfsddsd
!--Добавляем товары, но не сохраняем, жмем закрыть - видим алерт, что мы что-то не сохранили



8.3 Добавление данных о товаре в накладную без редактирования

Narrative:
Как заведующий отделом,
Я хочу добавить в накладную данные о принятых товарах,
Чтобы зафиксировать в системе факт прихода товара

Meta:
@sprint 5
@us 8.3

Scenario: Adding invoice products - 1 product with name autocomplete

Given there is the product with 'Тестовое имя 25-3' name, 'SKU-AIP1PWNAU' sku, 'BARCode-AIP1PWNAU' barcode
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-AIP1PWNAU' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Тестовое имя 25-3' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | Тестовое имя 25-3 |
| productSku | SKU-AIP1PWNAU |
| productBarCode | BARCode-AIP1PWNAU |
When the user inputs '5' in the invoice product 'productAmount' field
And the user inputs '5' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user checks the invoice product with 'SKU-AIP1PWNAU' sku is present
And the user checks the product with 'SKU-AIP1PWNAU' sku has values
| elementName | value |
| productName | Тестовое имя 25-3 |
| productSku | SKU-AIP1PWNAU |
| productBarcode | BARCode-AIP1PWNAU |
| productUnits | кг |
| productAmount | 5 |
| productPrice | 5 |
| productSum | 25 |
Then the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 25 |
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-AIP1PWNAU' sku is present
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete

Given there is the product with 'Тестовое имя AIP1PWSA' name, 'SKU-AIP1PWSA' sku, 'BARCode-AIP1PWSA' barcode, 'liter' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-AIP1PWSA' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
And the user inputs 'SKU-AIP1PWSA' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | Тестовое имя AIP1PWSA |
| productSku | SKU-AIP1PWSA |
| productBarCode | BARCode-AIP1PWSA |
When the user inputs '3' in the invoice product 'productAmount' field
And the user inputs '4' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user checks the invoice product with 'SKU-AIP1PWSA' sku is present
And the user checks the product with 'SKU-AIP1PWSA' sku has values
| elementName | value |
| productName | Тестовое имя AIP1PWSA |
| productSku | SKU-AIP1PWSA |
| productBarcode | BARCode-AIP1PWSA |
| productUnits | л |
| productAmount | 3 |
| productPrice | 4 |
| productSum | 12 |
Then the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 12 |
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-AIP1PWSA' sku is present
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete

Given there is the product with 'Тестовое имя AIP1PWBA' name, 'SKU-AIP1PWBA' sku, 'BARCode-AIP1PWBA' barcode, 'unit' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-AIP1PWBA' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
And the user inputs 'BARCode-AIP1PWBA' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | Тестовое имя AIP1PWBA |
| productSku | SKU-AIP1PWBA |
| productBarCode | BARCode-AIP1PWBA |
When the user inputs '45' in the invoice product 'productAmount' field
And the user inputs '12,32' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user checks the invoice product with 'SKU-AIP1PWBA' sku is present
And the user checks the product with 'SKU-AIP1PWBA' sku has values
| elementName | value |
| productName | Тестовое имя AIP1PWBA |
| productSku | SKU-AIP1PWBA |
| productBarcode | BARCode-AIP1PWBA |
| productUnits | шт |
| productAmount | 45 |
| productPrice | 12,32 |
| productSum |  554,4 |
Then the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum |  554,4 |
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-AIP1PWBA' sku is present
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation 0 symbols

Given there is the product with 'N-AIP1PWNAV0S' name, 'SKU-AIP1PWNAV0S' sku, 'BARCode-AIP1PWNAV0S' barcode
And there is the invoice with 'Invoice-AIP1PWNAV0S' sku
And the user logs in as 'departmentManager'
When the user inputs '!' in the invoice product 'productName' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation 1 symbols

Given there is the product with 'N-AIP1PWNAV1S' name, 'SKU-AIP1PWNAV1S' sku, 'BARCode-AIP1PWNAV1S' barcode
And there is the invoice with 'Invoice-AIP1PWNAV1S' sku
And the user logs in as 'departmentManager'
When the user inputs '!N' in the invoice product 'productName' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation 2 symbols

Given there is the product with 'N-AIP1PWNAV2S' name, 'SKU-AIP1PWNAV2S' sku, 'BARCode-AIP1PWNAV2S' barcode
And there is the invoice with 'Invoice-AIP1PWNAV2S' sku
And the user logs in as 'departmentManager'
When the user inputs '!N-' in the invoice product 'productName' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation 3 symbols

Given there is the product with 'N-AIP1PWNAV3S' name, 'SKU-AIP1PWNAV3S' sku, 'BARCode-AIP1PWNAV3S' barcode
And there is the invoice with 'Invoice-AIP1PWNAV3S' sku
And the user logs in as 'departmentManager'
When the user inputs '!N-A' in the invoice product 'productName' field
Then the users checks autocomplete results contains
| autocomlete result |
| N-AIP1PWNAV3S |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation 0 symbols

Given there is the product with 'N-AIP1PWSAV0S' name, 'SKU-AIP1PWSAV0S' sku, 'BARCode-AIP1PWSAV0S' barcode
And there is the invoice with 'Invoice-AIP1PWSAV0S' sku
And the user logs in as 'departmentManager'
When the user inputs '!' in the invoice product 'productSku' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation 1 symbols

Given there is the product with 'N-AIP1PWSAV1S' name, 'SKU-AIP1PWSAV1S' sku, 'BARCode-AIP1PWSAV1S' barcode
And there is the invoice with 'Invoice-AIP1PWSAV1S' sku
And the user logs in as 'departmentManager'
When the user inputs '!S' in the invoice product 'productSku' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation 2 symbols

Given there is the product with 'N-AIP1PWSAV2S' name, 'SKU-AIP1PWSAV2S' sku, 'BARCode-AIP1PWSAV2S' barcode
And there is the invoice with 'Invoice-AIP1PWSAV2S' sku
And the user logs in as 'departmentManager'
When the user inputs '!SK' in the invoice product 'productSku' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation 3 symbols

Given there is the product with 'N-AIP1PWSAV3S' name, 'SKU-AIP1PWSAV2S56' sku, 'BARCode-AIP1PWSAV2S' barcode
And there is the invoice with 'Invoice-AIP1PWSAV3S' sku
And the user logs in as 'departmentManager'
When the user inputs '!SKU' in the invoice product 'productSku' field
Then the users checks autocomplete results contains
| autocomlete result |
| SKU-AIP1PWSAV2S56 |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation 0 symbols

Given there is the product with 'N-AIP1PWBAV0S' name, 'SKU-AIP1PWBAV0S' sku, 'BARCode-AIP1PWBAV0S' barcode
And there is the invoice with 'Invoice-AIP1PWBAV0S' sku
And the user logs in as 'departmentManager'
When the user inputs '!' in the invoice product 'productBarCode' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation 1 symbols

Given there is the product with 'N-AIP1PWBAV1S' name, 'SKU-AIP1PWBAV1S' sku, 'BARCode-AIP1PWBAV1S' barcode
And there is the invoice with 'Invoice-AIP1PWBAV1S' sku
And the user logs in as 'departmentManager'
When the user inputs '!B' in the invoice product 'productBarCode' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation 2 symbols

Given there is the product with 'N-AIP1PWBAV2S' name, 'SKU-AIP1PWBAV2S' sku, 'BARCode-AIP1PWBAV2S' barcode
And there is the invoice with 'Invoice-AIP1PWBAV2S' sku
And the user logs in as 'departmentManager'
When the user inputs '!BA' in the invoice product 'productBarCode' field
Then the users checks no autocomplete results
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation 3 symbols

Given there is the product with 'N-AIP1PWBAV3S' name, 'SKU-AIP1PWBAV3S' sku, 'BARCode-AIP1PWBAV3S' barcode
And there is the invoice with 'Invoice-AIP1PWBAV3S' sku
And the user logs in as 'departmentManager'
When the user inputs '!BAR' in the invoice product 'productBarCode' field
Then the users checks autocomplete results contains
| autocomlete result |
| BARCode-AIP1PWBAV3S |
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation rus search

Given there is the product with 'Имя-AIP1WNAVRS' name, 'Артикул-AIP1WNAVRS' sku, 'Баркод-AIP1WNAVRS' barcode
And there is the invoice with 'Invoice-AIP1WNAVRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Имя-AIP1WNAVRS' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | Имя-AIP1WNAVRS |
| productSku | Артикул-AIP1WNAVRS |
| productBarCode | Баркод-AIP1WNAVRS |
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation numbers search

Given there is the product with '123-AIP1PWNAVNS' name, '123-AIP1PWNAVNS' sku, '123-AIP1PWNAVNS' barcode
And there is the invoice with 'Invoice-AIP1PWNAVNS' sku
And the user logs in as 'departmentManager'
When the user inputs '123-AIP1PWNAVNS' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | 123-AIP1PWNAVNS |
| productSku | 123-AIP1PWNAVNS |
| productBarCode | 123-AIP1PWNAVNS |
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation eng search

Given there is the product with 'NAME-AIP1PWNAVES' name, 'SKU-AIP1PWNAVES' sku, 'BC-AIP1PWNAVES' barcode
And there is the invoice with 'Invoice-AIP1PWNAVES' sku
And the user logs in as 'departmentManager'
When the user inputs 'NAME-AIP1PWNAVES' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | NAME-AIP1PWNAVES |
| productSku | SKU-AIP1PWNAVES |
| productBarCode | BC-AIP1PWNAVES |
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation symbols search

Given there is the product with '@#$-AIP1PWNAWSS' name, '@#$-AIP1PWNAWSS' sku, '@#$-AIP1PWNAWSS' barcode
And there is the invoice with 'Invoice-AIP1PWNAWSS' sku
And the user logs in as 'departmentManager'
When the user inputs '@#$-AIP1PWNAWSS' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | @#$-AIP1PWNAWSS |
| productSku | @#$-AIP1PWNAWSS |
| productBarCode | @#$-AIP1PWNAWSS |
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation small register search

Given there is the product with 'name-AIP1PWBAVSRS' name, 'sku-AIP1PWBAVSRS' sku, 'barcode-AIP1PWBAVSRS' barcode
And there is the invoice with 'Invoice-AIP1PWBAVSRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'name-AIP1PWBAVSRS' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | name-AIP1PWBAVSRS |
| productSku | sku-AIP1PWBAVSRS |
| productBarCode | barcode-AIP1PWBAVSRS |
When the user logs out

Scenario: Adding invoice products - 1 product with name autocomplete validation big register search

Given there is the product with 'Name-AIP1PWBAVBRS' name, 'Sku-AIP1PWBAVBRS' sku, 'Barcode-AIP1PWBAVBRS' barcode
And there is the invoice with 'Invoice-AIP1PWBAVBRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Name-AIP1PWBAVBRS' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-AIP1PWBAVBRS |
| productSku | Sku-AIP1PWBAVBRS |
| productBarCode | Barcode-AIP1PWBAVBRS |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation rus search

Given there is the product with 'Имя-AIP1WSAVRS' name, 'Артикул-AIP1WSAVRS' sku, 'Баркод-AIP1WSAVRS' barcode
And there is the invoice with 'Invoice-AIP1WSAVRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Артикул-AIP1WSAVRS' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | Имя-AIP1WSAVRS |
| productSku | Артикул-AIP1WSAVRS |
| productBarCode | Баркод-AIP1WSAVRS |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation numbers search

Given there is the product with '123-AIP1PWSAVNS' name, '123-AIP1PWSAVNS' sku, '123-AIP1PWSAVNS' barcode
And there is the invoice with 'Invoice-AIP1PWSAVNS' sku
And the user logs in as 'departmentManager'
When the user inputs '123-AIP1PWSAVNS' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | 123-AIP1PWSAVNS |
| productSku | 123-AIP1PWSAVNS |
| productBarCode | 123-AIP1PWSAVNS |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation eng search

Given there is the product with 'NAME-AIP1PWSAVES' name, 'SKU-AIP1PWSAVES' sku, 'BC-AIP1PWSAVES' barcode
And there is the invoice with 'Invoice-AIP1PWSAVES' sku
And the user logs in as 'departmentManager'
When the user inputs 'SKU-AIP1PWSAVES' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | NAME-AIP1PWSAVES |
| productSku | SKU-AIP1PWSAVES |
| productBarCode | BC-AIP1PWSAVES |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation symbols search

Given there is the product with '%^*-AIP1PWSAVSS' name, '%^*-AIP1PWSAVSS' sku, '%^*-AIP1PWSAVSS' barcode
And there is the invoice with 'Invoice-AIP1PWSAVSS' sku
And the user logs in as 'departmentManager'
When the user inputs '%^*-AIP1PWSAVSS' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | %^*-AIP1PWSAVSS |
| productSku | %^*-AIP1PWSAVSS |
| productBarCode | %^*-AIP1PWSAVSS |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation small register search

Given there is the product with 'name-AIP1PWSAVSS' name, 'sku-AIP1PWSAVSS' sku, 'barcode-AIP1PWSAVSS' barcode
And there is the invoice with 'Invoice-AIP-1PWSAVSRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'sku-AIP1PWSAVSS' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | name-AIP1PWSAVSS |
| productSku | sku-AIP1PWSAVSS |
| productBarCode | barcode-AIP1PWSAVSS |
When the user logs out

Scenario: Adding invoice products - 1 product with sku autocomplete validation big register search

Given there is the product with 'Name-AIP1PWSAVSS' name, 'Sku-AIP1PWSAVSS' sku, 'Barcode-AIP1PWSAVSS' barcode
And there is the invoice with 'Invoice-AIP-1PWSAVBRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Sku-AIP1PWSAVSS' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-AIP1PWSAVSS |
| productSku | Sku-AIP1PWSAVSS |
| productBarCode | Barcode-AIP1PWSAVSS |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation rus search

Given there is the product with 'Имя-AIP1PWBAVRS' name, 'Артикул-AIP1PWBAVRS' sku, 'Баркод-AIP1PWBAVRS' barcode
And there is the invoice with 'Invoice-AIP1PWBAVRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Баркод-AIP1PWBAVRS' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | Имя-AIP1PWBAVRS |
| productSku | Артикул-AIP1PWBAVRS |
| productBarCode | Баркод-AIP1PWBAVRS |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation numbers search

Given there is the product with '123-AIP1PWBAVNS' name, '123-AIP1PWBAVNS' sku, '123-AIP1PWBAVNS' barcode
And there is the invoice with 'Invoice-AIP1PWBAVNS' sku
And the user logs in as 'departmentManager'
When the user inputs '123-AIP1PWBAVNS' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | 123-AIP1PWBAVNS |
| productSku | 123-AIP1PWBAVNS |
| productBarCode | 123-AIP1PWBAVNS |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation eng search

Given there is the product with 'NAME-AIP1PWBAVES' name, 'SKU-AIP1PWBAVES' sku, 'BC-AIP1PWBAVES' barcode
And there is the invoice with 'Invoice-AIP1PWBAVES' sku
And the user logs in as 'departmentManager'
When the user inputs 'BC-AIP1PWBAVES' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | NAME-AIP1PWBAVES |
| productSku | SKU-AIP1PWBAVES |
| productBarCode | BC-AIP1PWBAVES |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation symbols search

Given there is the product with '()_+-AIP1PWBAVES' name, '()_+-AIP1PWBAVES' sku, '()_+-AIP1PWBAVES' barcode
And there is the invoice with 'Invoice-AIP-1PWBAVSS' sku
And the user logs in as 'departmentManager'
When the user inputs '()_+-AIP1PWBAVES' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | ()_+-AIP1PWBAVES |
| productSku | ()_+-AIP1PWBAVES |
| productBarCode | ()_+-AIP1PWBAVES |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation small register search

Given there is the product with 'name-AIP1PWBAVES' name, 'sku-AIP1PWBAVES' sku, 'barcode-AIP1PWBAVES' barcode
And there is the invoice with 'Invoice-AIP-1PWBAVSRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'barcode-AIP1PWBAVES' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | name-AIP1PWBAVES |
| productSku | sku-AIP1PWBAVES |
| productBarCode | barcode-AIP1PWBAVES |
When the user logs out

Scenario: Adding invoice products - 1 product with barcode autocomplete validation big register search

Given there is the product with 'Name-AIP1PWBAVES' name, 'Sku-AIP1PWBAVES' sku, 'Barcode-AIP1PWBAVES' barcode
And there is the invoice with 'Invoice-AIP-1PWBAVBRS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Barcode-AIP1PWBAVES' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-AIP1PWBAVES |
| productSku | Sku-AIP1PWBAVES |
| productBarCode | Barcode-AIP1PWBAVES |
When the user logs out

Scenario: Clearing the fields if another autocomplete is inputed - name

Given there is the product with 'Name-CLTFIAAIIN' name, 'Sku-CLTFIAAIIN' sku, 'Barcode-CLTFIAAIIN' barcode
And there is the invoice with 'Invoice-CLTFIAAIIN' sku
And the user logs in as 'departmentManager'
When the user inputs 'Name-CLTFIAAIIN' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-CLTFIAAIIN |
| productSku | Sku-CLTFIAAIIN |
| productBarCode | Barcode-CLTFIAAIIN |
When the user inputs '!' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName |  |
| productSku |  |
| productBarCode |  |
When the user logs out

Scenario: Clearing the fields if another autocomplete is inputed - sku

Given there is the product with 'Name-CLTFIAAIIS' name, 'Sku-CLTFIAAIIS' sku, 'Barcode-CLTFIAAIIS' barcode
And there is the invoice with 'Invoice-CLTFIAAIIS' sku
And the user logs in as 'departmentManager'
When the user inputs 'Sku-CLTFIAAIIS' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-CLTFIAAIIS |
| productSku | Sku-CLTFIAAIIS |
| productBarCode | Barcode-CLTFIAAIIS |
When the user inputs '!' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName |  |
| productSku |  |
| productBarCode |  |
When the user logs out

Scenario: Clearing the fields if another autocomplete is inputed - barcode

Given there is the product with 'Name-CLTFIAAIIB' name, 'Sku-CLTFIAAIIB' sku, 'Barcode-CLTFIAAIIB' barcode
And there is the invoice with 'Invoice-CLTFIAAIIB' sku
And the user logs in as 'departmentManager'
When the user inputs 'Barcode-CLTFIAAIIB' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-CLTFIAAIIB |
| productSku | Sku-CLTFIAAIIB |
| productBarCode | Barcode-CLTFIAAIIB |
When the user inputs '!' in the invoice product 'productBarCode' field
Then the user checks invoice elements values
| elementName | value |
| productName |  |
| productSku |  |
| productBarCode |  |
When the user logs out

Scenario: Adding invoice products - 3 products with barcode, name, sku autocomplete

Given there is the product with 'Тестовый продукт-1' name, 'Тестовый артикул-1' sku, 'Тестовый баркод-1' barcode
And there is the product with 'Тестовый продукт-2' name, 'Тестовый артикул-2' sku, 'Тестовый баркод-2' barcode, 'liter' units
And there is the product with 'Тестовый продукт-3' name, 'Тестовый артикул-3' sku, 'Тестовый баркод-3' barcode, 'unit' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-AIP2PWBNA-1' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
And the user inputs 'Тестовый продукт-1' in the invoice product 'productName' field
Then the user checks invoice elements values
| elementName | value |
| productName | Тестовый продукт-1 |
| productSku | Тестовый артикул-1 |
| productBarCode |Тестовый баркод-1 |
When the user inputs '2' in the invoice product 'productAmount' field
And the user inputs '5,4' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user checks the invoice product with 'Тестовый артикул-1' sku is present
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 10,8 |
When the user inputs 'Тестовый артикул-2' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | Тестовый продукт-2 |
| productSku | Тестовый артикул-2 |
| productBarCode |Тестовый баркод-2 |
When the user inputs '3' in the invoice product 'productAmount' field
And the user inputs '1,2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user checks the invoice product with 'Тестовый артикул-2' sku is present
And the user checks invoice elements values
| elementName | value |
| totalProducts | 2 |
| totalSum | 14,40 |
When the user inputs 'Тестовый артикул-3' in the invoice product 'productSku' field
Then the user checks invoice elements values
| elementName | value |
| productName | Тестовый продукт-3 |
| productSku | Тестовый артикул-3 |
| productBarCode |Тестовый баркод-3 |
When the user inputs '2' in the invoice product 'productAmount' field
And the user inputs '20' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user checks the invoice product with 'Тестовый артикул-3' sku is present
And the user checks invoice elements values
| elementName | value |
| totalProducts | 3 |
| totalSum | 54,40 |
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-AIP2PWBNA-1' sku is present
When the user logs out









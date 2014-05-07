История редактирования товара

Narrative:
Как коммерческий директор,
Я хочу изменять данные товара,
Чтобы актуализировать эти данные и исправлять в них ошибки

Meta:
@sprint_33
@us_69
@product
@s33u69s10

Scenario: Product edit main

Meta:
@s33u69s10e01

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Штучный'
And the user inputs values in element fields
| elementName | value |
| name | Наименование1688 |
| vendor | Производитель1688 |
| vendorCountry | Россия1688 |
| purchasePrice | 1231688 |
| barcode | 1231688 |
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
And the user open the product card with 'Наименование1688' name
Then the user checks the elements values
| elementName | value  |
| name | Наименование1688 |
| vendor | Производитель1688 |
| vendorCountry | Россия1688 |
| purchasePrice | 1 231 688,00 |
| barcode | 1231688 |
| units | Штуки |
| vat | 10 |
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| purchasePrice | 8922174 |
| barcode | 102454 |
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | value  |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| vendorCountry |  |
| purchasePrice | 8 922 174,00 |
| barcode | 102454 |
| units | Штуки |
| vat | 0 |

Scenario: Product edit check all dropdown values

Meta:
@s33u69s10e02

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Штучный'
And the user inputs values in element fields
| elementName | value |
| name | Наименование16888 |
| vendor | Производитель16888 |
| vendorCountry | Россия16888 |
| purchasePrice | 1231 |
| purchasePrice | 1231 |
| barcode | 12316888 |
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
When the user open the product card with 'Наименование16888' name
When the user clicks the edit button on product card view page
And the user selects '18' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | value  |
| type | Штучный |
| units | Штуки |
| vat | 18 |
When the user clicks the edit button on product card view page
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | value  |
| units | Штуки |
| vat | 10 |
When the user clicks the edit button on product card view page
And the user selects product type 'Весовой'
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | value  |
| type | Весовой |
| units | Килограммы |
| vat | 0 |


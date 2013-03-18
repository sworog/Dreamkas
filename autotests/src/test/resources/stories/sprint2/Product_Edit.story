История редактирования товара

Narrative:
Как коммерческий директор,
Я хочу изменять данные товара,
Чтобы актуализировать эти данные и исправлять в них ошибки

Scenario: Product edit main
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs values in element fields
| elementName | inputText |
| name | Наименование1688 |
| vendor | Производитель1688 |
| vendorCountry | Россия1688 |
| purchasePrice | 1231688 |
| barcode | 1231688 |
| sku | 1688 |
| info | Info1688 |
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
When the user open the product card with '1688' sku
Then the user checks the elements values
| elementName | expectedValue  |
| sku | 1688 |
| name | Наименование1688 |
| vendor | Производитель1688 |
| vendorCountry | Россия1688 |
| purchasePrice | 1231688 |
| barcode | 1231688 |
| unit | штука |
| vat | 10 |
| info | Info1688 |
When the user clicks the edit button on product card view page
And the user edits values in element fields
| elementName | inputText |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| purchasePrice | 8922174 |
| barcode | 102454 |
| sku | 89489545DGF1 |
| info | Info1688 |
And the user selects 'liter' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | expectedValue  |
| sku | 89489545DGF1 |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| vendorCountry |  |
| purchasePrice | 8922174 |
| barcode | 102454 |
| unit | литр |
| vat | 0 |
| info | Info1688 |

Scenario: Product edit check all dropdawn values
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs values in element fields
| elementName | inputText |
| name | Наименование16888 |
| vendor | Производитель16888 |
| vendorCountry | Россия16888 |
| purchasePrice | 1231 |
| purchasePrice | 1231 |
| barcode | 12316888 |
| sku | 16888 |
| info | Info16888 |
And the user selects 'liter' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
When the user open the product card with '16888' sku
When the user clicks the edit button on product card view page
And the user selects 'kg' in 'unit' dropdown
And the user selects '18' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | expectedValue  |
| unit | килограмм |
| vat | 18 |
When the user clicks the edit button on product card view page
And the user selects 'liter' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | expectedValue  |
| unit | литр |
| vat | 10 |
When the user clicks the edit button on product card view page
And the user selects 'unit' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
Then the user checks the elements values
| elementName | expectedValue  |
| unit | штука |
| vat | 0 |

Scenario: product edit cancel
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs values in element fields
| elementName | inputText |
| name | Наименование9001 |
| vendor | Производитель9001 |
| vendorCountry | Россия9001 |
| purchasePrice | 1231 |
| barcode | 1231689001 |
| sku | 169001 |
| info | Info169001 |
And the user selects 'liter' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user clicks the create button
And the user open the product card with '169001' sku
And the user clicks the edit button on product card view page
And the user clicks the cancel button
Then the user checks the elements values
| elementName | expectedValue  |
| sku | 169001 |
| name | Наименование9001 |

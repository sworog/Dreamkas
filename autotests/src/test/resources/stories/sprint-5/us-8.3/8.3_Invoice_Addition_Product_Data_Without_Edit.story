8.3 Добавление данных о товаре в накладную без редактирования

Narrative:
Как заведующий отделом,
Я хочу добавить в накладную данные о принятых товарах,
Чтобы зафиксировать в системе факт прихода товара

Meta:
@sprint_5
@us_8.3

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

Scenario: Autocomplete validation 0/1/2 symbols

Given there is the product with 'N-AIP1PWNAV0S' name, 'SKU-AIP1PWNAV0S' sku, 'BARCode-AIP1PWNAV0S' barcode
And there is the invoice with 'Invoice-AIP1PWNAV0S' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWNAV0S'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the users checks no autocomplete results

Examples:
| value | elementName |
| ! | productName |
| !N | productName |
| !N- | productName |
| ! | productSku |
| !S | productSku |
| !SK | productSku |
| ! | productBarCode |
| !B | productBarCode |
| !BA | productBarCode |

Scenario: Adding invoice products - autocomplete validation 3 symbols

Given there is the product with 'N-AIP1PWNAV3S' name, 'SKU-AIP1PWNAV3S' sku, 'BARCode-AIP1PWNAV3S' barcode
And there is the invoice with 'Invoice-AIP1PWNAV3S' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWNAV3S'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks <autoCompleteResult>

Examples:
| value | elementName | autoCompleteResult |
| !N-A | productName | N-AIP1PWNAV3S |
| !SKU | productSku | SKU-AIP1PWNAV3S |
| !BAR | productBarCode | BARCode-AIP1PWNAV3S |

Scenario: Adding invoice products - autocomplete validation rus search

Given there is the product with 'Имя-AIP1WNAVRS' name, 'Артикул-AIP1WNAVRS' sku, 'Баркод-AIP1WNAVRS' barcode
And there is the invoice with 'Invoice-AIP1WNAVRS' sku
And the user navigates to the invoice page with name 'Invoice-AIP1WNAVRS'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks invoice elements values
| elementName | value |
| productName | Имя-AIP1WNAVRS |
| productSku | Артикул-AIP1WNAVRS |
| productBarCode | Баркод-AIP1WNAVRS |

Examples:
| value | elementName |
| Имя-AIP1WNAVRS | productName |
| Артикул-AIP1WNAVRS | productSku |
| Баркод-AIP1WNAVRS | productBarCode |

Scenario: Adding invoice products - autocomplete validation numbers search

Given there is the product with '123-AIP1PWNAVNS' name, '123-AIP1PWNAVNS' sku, '123-AIP1PWNAVNS' barcode
And there is the invoice with 'Invoice-AIP1PWNAVNS' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWNAVNS'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks invoice elements values
| elementName | value |
| productName | 123-AIP1PWNAVNS |
| productSku | 123-AIP1PWNAVNS |
| productBarCode | 123-AIP1PWNAVNS |

Examples:
| value | elementName |
| 123-AIP1PWNAVNS | productName |
| 123-AIP1PWNAVNS | productSku |
| 123-AIP1PWNAVNS | productBarCode |

Scenario: Adding invoice products - autocomplete validation eng search

Given there is the product with 'NAME-AIP1PWNAVES' name, 'SKU-AIP1PWNAVES' sku, 'BC-AIP1PWNAVES' barcode
And there is the invoice with 'Invoice-AIP1PWNAVES' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWNAVES'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks invoice elements values
| elementName | value |
| productName | NAME-AIP1PWNAVES |
| productSku | SKU-AIP1PWNAVES |
| productBarCode | BC-AIP1PWNAVES |

Examples:
| value | elementName |
| NAME-AIP1PWNAVES | productName |
| SKU-AIP1PWNAVES | productSku |
| BC-AIP1PWNAVES | productBarCode |

Scenario: Adding invoice products - autocomplete validation symbols search

Given there is the product with '@#$-AIP1PWNAWSS' name, '@#$-AIP1PWNAWSS' sku, '@#$-AIP1PWNAWSS' barcode
And there is the invoice with 'Invoice-AIP1PWNAWSS' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWNAWSS'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks invoice elements values
| elementName | value |
| productName | @#$-AIP1PWNAWSS |
| productSku | @#$-AIP1PWNAWSS |
| productBarCode | @#$-AIP1PWNAWSS |

Examples:
| value | elementName |
| @#$-AIP1PWNAWSS | productName |
| @#$-AIP1PWNAWSS | productSku |
| @#$-AIP1PWNAWSS | productBarCode |

Scenario: Adding invoice products - autocomplete validation small register search

Given there is the product with 'name-AIP1PWBAVSRS' name, 'sku-AIP1PWBAVSRS' sku, 'barcode-AIP1PWBAVSRS' barcode
And there is the invoice with 'Invoice-AIP1PWBAVSRS' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWBAVSRS'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks invoice elements values
| elementName | value |
| productName | name-AIP1PWBAVSRS |
| productSku | sku-AIP1PWBAVSRS |
| productBarCode | barcode-AIP1PWBAVSRS |

Examples:
| value | elementName |
| name-AIP1PWBAVSRS | productName |
| sku-AIP1PWBAVSRS | productSku |
| barcode-AIP1PWBAVSRS | productBarCode |

Scenario: Adding invoice products - autocomplete validation big register search

Given there is the product with 'Name-AIP1PWBAVBRS' name, 'Sku-AIP1PWBAVBRS' sku, 'Barcode-AIP1PWBAVBRS' barcode
And there is the invoice with 'Invoice-AIP1PWBAVBRS' sku
And the user navigates to the invoice page with name 'Invoice-AIP1PWBAVBRS'
And the user logs in as 'departmentManager'
When the user inputs <value> in the invoice product <elementName> field
Then the user checks invoice elements values
| elementName | value |
| productName | Name-AIP1PWBAVBRS |
| productSku | Sku-AIP1PWBAVBRS |
| productBarCode | Barcode-AIP1PWBAVBRS |

Examples:
| value | elementName |
| Name-AIP1PWBAVBRS | productName |
| Sku-AIP1PWBAVBRS | productSku |
| Barcode-AIP1PWBAVBRS | productBarCode |

Scenario: Clearing the fields if another autocomplete is inputed - name

Given there is the product with 'Name-CLTFIAAIIN' name, 'Sku-CLTFIAAIIN' sku, 'Barcode-CLTFIAAIIN' barcode
And there is the invoice with 'Invoice-CLTFIAAIIN' sku
And the user navigates to the invoice page with name 'Invoice-CLTFIAAIIN'
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

Scenario: Clearing the fields if another autocomplete is inputed - sku

Given there is the product with 'Name-CLTFIAAIIS' name, 'Sku-CLTFIAAIIS' sku, 'Barcode-CLTFIAAIIS' barcode
And there is the invoice with 'Invoice-CLTFIAAIIS' sku
And the user navigates to the invoice page with name 'Invoice-CLTFIAAIIS'
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

Scenario: Clearing the fields if another autocomplete is inputed - barcode

Given there is the product with 'Name-CLTFIAAIIB' name, 'Sku-CLTFIAAIIB' sku, 'Barcode-CLTFIAAIIB' barcode
And there is the invoice with 'Invoice-CLTFIAAIIB' sku
And the user navigates to the invoice page with name 'Invoice-CLTFIAAIIB'
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









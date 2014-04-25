11.1 Последняя и средняя цены закупки товара на странице просмотра остатков

Narrative:
Как заведующий отделом,
При присмотре товарных остатков
Я хочу видеть последнюю закупочную цену товара,
Чтобы отталкиваться от нее при заказе товара и переговорах с поставщиками

Meta:
@sprint_8
@us_11.1

Scenario: Average price is not changed in current day

Meta:
@smoke
@id_s8u11.1s1

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье Московское' name, '45695514566' barcode, 'liter' units, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Московское-Артикул |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Московское | Печенье-Московское-Артикул | 45695514566 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Московское-Артикул |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Московское | Печенье-Московское-Артикул | 45695514566 | 20,0 | 0,0 | 0,0 | 56,00 р. | 26,00 р.|

Scenario: Average price is changed in 30 days

Meta:
@smoke
@id_s8u11.1s2

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье Питерское' name, '45695514566' barcode, 'liter' units, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Питерское-Артикул |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Питерское | Печенье-Питерское-Артикул | 45695514566 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-30day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Питерское-Артикул |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Питерское | Печенье-Питерское-Артикул | 45695514566 | 20,0 | 0,0 | 0,0 | 26,00 р. | 41,00 р.|

Scenario: Average price is not changed above 30 days

Meta:
@smoke
@id_s8u11.1s3

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье Гламурное' name, '45695514566' barcode, 'liter' units, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Гламурное-Артикул |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Гламурное | Печенье-Гламурное-Артикул | 45695514566 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-30day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Гламурное-Артикул |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Гламурное | Печенье-Гламурное-Артикул | 45695514566 | 20,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Scenario: Average price is changed in 1 days

Meta:
@smoke
@id_s8u11.1s4

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье Астраханское' name, '45695514566' barcode, 'liter' units, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Астраханское-Артикул |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Астраханское | Печенье-Астраханское-Артикул | 45695514566 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-1day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Астраханское-Артикул |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Астраханское | Печенье-Астраханское-Артикул | 45695514566 | 20,0 | 0,0 | 0,0 | 56,00 р. | 41,00 р.|

Scenario: Average price round checking

Meta:
@smoke
@id_s8u11.1s5

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье Киевское' name, '45695514566' barcode, 'liter' units, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Киевское-Артикул |
| quantity | 10 |
| price | 23,33 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Киевское | Печенье-Киевское-Артикул | 45695514566 | 10,0 | 0,0 | 0,0 | 23,33 р. | 23,33 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-1day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Киевское-Артикул |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Киевское | Печенье-Киевское-Артикул | 45695514566 | 20,0 | 0,0 | 0,0 | 26,00 р. | 24,67 р.|

Scenario: Average price calculation

Meta:
@smoke
@id_s8u11.1s6

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье Юбилейное' name, '45695514566' barcode, 'liter' units, '25,50' purchasePrice
Given there is the product with 'name-s8u111' name, 'barcode-s8u111' barcode, 'liter' units, '25,50' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Юбилейное-Артикул |
| quantity | 10 |
| price | 26 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | sku-s8u111 |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-10day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Юбилейное-Артикул |
| quantity | 5 |
| price | 29 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | sku-s8u111 |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-4day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Юбилейное-Артикул |
| quantity | 10 |
| price | 31 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | sku-s8u111 |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Юбилейное | Печенье-Юбилейное-Артикул | 45695514566 | 25,0 | 0,0 | 0,0 | 31,00 р. | 28,60 р.|

Given the user opens last created invoice page

When the user clicks on the invoice product by name 'Печенье Юбилейное'
And the user clicks on delete icon and deletes invoice product with name 'Печенье Юбилейное'

When the user accepts products and saves the invoice

Given the user runs the recalculate_metrics cap command
And the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Юбилейное | Печенье-Юбилейное-Артикул | 45695514566 | 15,0 | 0,0 | 0,0 | 29,00 р. | 27,00 р.|

Given the user opens one invoice ago created invoice page

When the user clicks on the invoice product by name 'Печенье Юбилейное'
And the user clicks on delete icon and deletes invoice product with name 'Печенье Юбилейное'

When the user accepts products and saves the invoice

Given the user runs the recalculate_metrics cap command
And the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Юбилейное | Печенье-Юбилейное-Артикул | 45695514566 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user opens two invoice ago created invoice page

When the user clicks on the invoice product by name 'Печенье Юбилейное'
And the user clicks on delete icon and deletes invoice product with name 'Печенье Юбилейное'

When the user accepts products and saves the invoice

Given the user runs the recalculate_metrics cap command
And the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье Юбилейное | Печенье-Юбилейное-Артикул | 45695514566 | 0,0 | 0,0 | 0,0 | 25,50 р. | — |
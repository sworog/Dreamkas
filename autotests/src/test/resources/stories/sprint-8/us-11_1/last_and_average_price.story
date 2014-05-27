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

Given there is the product with 'Печенье-Московское' name, '456955145661' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Московское |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Московское | #sku:Печенье-Московское | 456955145661 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Московское |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Московское | #sku:Печенье-Московское | 456955145661 | 20,0 | 0,0 | 0,0 | 56,00 р. | 26,00 р.|

Scenario: Average price is changed in 30 days

Meta:
@smoke
@id_s8u11.1s2

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье-Питерское' name, '456955145662' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Питерское |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Питерское | #sku:Печенье-Питерское | 456955145662 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-30day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Питерское |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Питерское | #sku:Печенье-Питерское | 456955145662 | 20,0 | 0,0 | 0,0 | 26,00 р. | 41,00 р.|

Scenario: Average price is not changed above 30 days

Meta:
@smoke
@id_s8u11.1s3

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье-Гламурное' name, '456955145663' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Гламурное |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Гламурное | #sku:Печенье-Гламурное | 456955145663 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-30day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Гламурное |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Гламурное | #sku:Печенье-Гламурное | 456955145663 | 20,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Scenario: Average price is changed in 1 days

Meta:
@smoke
@id_s8u11.1s4

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье-Астраханское' name, '456955145664' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Астраханское |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Астраханское | #sku:Печенье-Астраханское | 456955145664 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-1day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Астраханское |
| quantity | 10 |
| price | 56 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Астраханское | #sku:Печенье-Астраханское | 456955145664 | 20,0 | 0,0 | 0,0 | 56,00 р. | 41,00 р.|

Scenario: Average price round checking

Meta:
@smoke
@id_s8u11.1s5

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье-Киевское' name, '456955145665' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Киевское |
| quantity | 10 |
| price | 23,33 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Киевское | #sku:Печенье-Киевское | 456955145665 | 10,0 | 0,0 | 0,0 | 23,33 р. | 23,33 р.|

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-1day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Киевское |
| quantity | 10 |
| price | 26 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command
And the user refreshes the current page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Киевское | #sku:Печенье-Киевское | 456955145665 | 20,0 | 0,0 | 0,0 | 26,00 р. | 24,67 р.|

Scenario: Average price calculation

Meta:
@smoke
@id_s8u11.1s6

Given there is the user with name 'departmentManager-s8u111', position 'departmentManager-s8u111', username 'departmentManager-s8u111', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s8u111' managed by department manager named 'departmentManager-s8u111'

Given there is the product with 'Печенье-Юбилейное' name, '456955145666' barcode, 'unit' type, '25,50' purchasePrice
Given there is the product with 'name-s8u111' name, 'barcode-s8u111' barcode, 'unit' type, '25,50' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime-15day |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | Печенье-Юбилейное |
| quantity | 10 |
| price | 26 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-s8u111 |
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
| productName | Печенье-Юбилейное |
| quantity | 5 |
| price | 29 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-s8u111 |
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
| productName | Печенье-Юбилейное |
| quantity | 10 |
| price | 31 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-s8u111 |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s8u111'

Given the user runs the recalculate_metrics cap command

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-s8u111' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Юбилейное | #sku:Печенье-Юбилейное | 456955145666 | 25,0 | 0,0 | 0,0 | 31,00 р. | 28,60 р.|

Given the user opens last created invoice page

When the user clicks on the invoice product by name 'Печенье-Юбилейное'
And the user clicks on delete icon and deletes invoice product with name 'Печенье-Юбилейное'

When the user accepts products and saves the invoice

Given the user runs the recalculate_metrics cap command
And the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Юбилейное | #sku:Печенье-Юбилейное | 456955145666 | 15,0 | 0,0 | 0,0 | 29,00 р. | 27,00 р.|

Given the user opens one invoice ago created invoice page

When the user clicks on the invoice product by name 'Печенье-Юбилейное'
And the user clicks on delete icon and deletes invoice product with name 'Печенье-Юбилейное'

When the user accepts products and saves the invoice

Given the user runs the recalculate_metrics cap command
And the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Юбилейное | #sku:Печенье-Юбилейное | 456955145666 | 10,0 | 0,0 | 0,0 | 26,00 р. | 26,00 р.|

Given the user opens two invoice ago created invoice page

When the user clicks on the invoice product by name 'Печенье-Юбилейное'
And the user clicks on delete icon and deletes invoice product with name 'Печенье-Юбилейное'

When the user accepts products and saves the invoice

Given the user runs the recalculate_metrics cap command
And the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Печенье-Юбилейное | #sku:Печенье-Юбилейное | 456955145666 | 0,0 | 0,0 | 0,0 | 25,50 р. | — |
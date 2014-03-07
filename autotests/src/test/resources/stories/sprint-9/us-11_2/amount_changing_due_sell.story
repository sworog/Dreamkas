11.2 Изменение остатков в связи с продажей товара

Narrative:
Как заведующий отделом,
Я хочу чтобы продажи изменяли остатки по товару,
Чтобы определять когда и какого объема требуется размещать заказ у поставщика

Meta:
@sprint_9
@us_11.2

Scenario: Selling more products amounts then had

Given skipped test
Given there is the product with 'Хлопья Питерские' name, 'Хлопья-Питерские-Артикул' sku, '45695514566' barcode, 'liter' units, '15' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'AmVeDuSe-2' in the invoice 'sku' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
And the user inputs 'Хлопья Питерские' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '16' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Given the user is on the invoice list page
Then the user checks the invoice with 'AmVeDuSe-2' sku is present
Given the user opens amount list page
Then the user checks the product with 'Хлопья-Питерские-Артикул' sku has 'amounts amount' element equal to '1' on amounts page
Given the user opens sales emulator page
When the user adds the product with 'Хлопья Питерские' name, '100' quantity and '18' price to bill
And the user makes the purchase
Given the user opens amount list page
Then the user checks the product with 'Хлопья-Питерские-Артикул' sku has 'amounts amount' element equal to '-99' on amounts page
11 Просмотр остатков

Narrative:
Как заведующий отделом,
Я хочу просматривать товарные остатки,
Чтобы определять когда и какого объема требуется размещать заказ у поставщика

Meta:
@sprint 6
@us 11

Scenario: Amounts increase kg
Given there is the product with 'ADADAD-11' name, 'ADADAD-11' sku, 'BARCode-11' barcode
And there is the invoice with 'Invoice-ADADAD-11' sku
And the user logs in as 'departmentManager'
When the user inputs 'ADADAD-11' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-ADADAD-11' sku is present
Given the user opens amount list page
Then the user checks the product with 'ADADAD-11' sku has 'amount' equal to '1' on amounts page
And the user checks the product with 'ADADAD-11' sku has 'units' equal to 'кг' on amounts page
Given there is the invoice with 'Invoice-ADADAD-112' sku
When the user inputs 'ADADAD-11' in the invoice product 'productName' field
And the user inputs '3' in the invoice product 'productAmount' field
And the user inputs '111,34' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-ADADAD-112' sku is present
Given the user opens amount list page
Then the user checks the product with 'ADADAD-11' sku has 'amounts amount' element equal to '4' on amounts page
And the user checks the product with 'ADADAD-11' sku has 'amounts units' element equal to 'кг' on amounts page
And the user checks the product with 'ADADAD-11' sku has 'amounts name' element equal to 'ADADAD-11' on amounts page
And the user checks the product with 'ADADAD-11' sku has 'amounts vendor' element equal to 'Тестовый производитель' on amounts page
And the user checks the product with 'ADADAD-11' sku has 'amounts vendorCountry' element equal to 'Тестовая страна' on amounts page
And the user checks the product with 'ADADAD-11' sku has 'amounts purchasePrice' element equal to '111,34' on amounts page
When the user logs out

Scenario: Amounts increase units
Given there is the product with 'ADADAD-22' name, 'ADADAD-22' sku, 'BARCode-22' barcode, 'unit' units
And there is the invoice with 'Invoice-ADADAD-22' sku
And the user logs in as 'departmentManager'
When the user inputs 'ADADAD-22' in the invoice product 'productName' field
And the user inputs '12' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-ADADAD-22' sku is present
Given the user opens amount list page
Then the user checks the product with 'ADADAD-22' sku has 'amount' equal to '12' on amounts page
And the user checks the product with 'ADADAD-22' sku has 'units' equal to 'шт.' on amounts page
Given there is the invoice with 'Invoice-ADADAD-221' sku
When the user inputs 'ADADAD-22' in the invoice product 'productName' field
And the user inputs '26' in the invoice product 'productAmount' field
And the user inputs '45.6' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-ADADAD-221' sku is present
Given the user opens amount list page
Then the user checks the product with 'ADADAD-22' sku has 'amounts amount' element equal to '38' on amounts page
And the user checks the product with 'ADADAD-22' sku has 'amounts units' element equal to 'шт.' on amounts page
And the user checks the product with 'ADADAD-22' sku has 'amounts name' element equal to 'ADADAD-22' on amounts page
And the user checks the product with 'ADADAD-22' sku has 'amounts vendor' element equal to 'Тестовый производитель' on amounts page
And the user checks the product with 'ADADAD-22' sku has 'amounts vendorCountry' element equal to 'Тестовая страна' on amounts page
And the user checks the product with 'ADADAD-22' sku has 'amounts purchasePrice' element equal to '45,6' on amounts page
When the user logs out

Scenario: Amount increase liter
Given there is the product with 'ADADAD-33' name, 'ADADAD-33' sku, 'BARCode-33' barcode, 'liter' units
And there is the invoice with 'Invoice-ADADAD-33' sku
And the user logs in as 'departmentManager'
When the user inputs 'ADADAD-33' in the invoice product 'productName' field
And the user inputs '45' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-ADADAD-33' sku is present
Given the user opens amount list page
Then the user checks the product with 'ADADAD-33' sku has 'amount' equal to '45' on amounts page
And the user checks the product with 'ADADAD-33' sku has 'units' equal to 'л' on amounts page
Given there is the invoice with 'Invoice-ADADAD-331' sku
When the user inputs 'ADADAD-33' in the invoice product 'productName' field
And the user inputs '125' in the invoice product 'productAmount' field
And the user inputs '34' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-ADADAD-331' sku is present
Given the user opens amount list page
Then the user checks the product with 'ADADAD-33' sku has 'amounts amount' element equal to '170' on amounts page
And the user checks the product with 'ADADAD-33' sku has 'amounts units' element equal to 'л' on amounts page
And the user checks the product with 'ADADAD-33' sku has 'amounts name' element equal to 'ADADAD-33' on amounts page
And the user checks the product with 'ADADAD-33' sku has 'amounts vendor' element equal to 'Тестовый производитель' on amounts page
And the user checks the product with 'ADADAD-33' sku has 'amounts vendorCountry' element equal to 'Тестовая страна' on amounts page
And the user checks the product with 'ADADAD-33' sku has 'amounts purchasePrice' element equal to '34' on amounts page
When the user logs out







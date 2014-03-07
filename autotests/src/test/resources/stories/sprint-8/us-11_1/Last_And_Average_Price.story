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

Given there is the product with 'Печенье Московское' name, 'Печенье-Московское-Артикул' sku, '45695514566' barcode, 'liter' units, '15' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD2' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Московское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '26' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD2' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Московское-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Московское-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD3' in the invoice 'sku' field
And the user inputs 'todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Московское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '56' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD3' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Московское-Артикул' sku has 'amounts purchasePrice' element equal to '56' on amounts page
And the user checks the product with 'Печенье-Московское-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page

Scenario: Average price is changed in 30 days

Given there is the product with 'Печенье Питерское' name, 'Печенье-Питерское-Артикул' sku, '45695514566' barcode, 'liter' units, '15' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Питерское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '26' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Питерское-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Питерское-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD1' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-30day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Питерское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '56' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD1' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Питерское-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Питерское-Артикул' sku has 'amounts averagePrice' element equal to '41' on amounts page

Scenario: Average price is not changed above 30 days

Given there is the product with 'Печенье Гламурное' name, 'Печенье-Гламурное-Артикул' sku, '45695514566' barcode, 'liter' units, '15' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD5' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Гламурное' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '26' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD5' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Гламурное-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Гламурное-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD6' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-31day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Гламурное' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '56' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD6' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Гламурное-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Гламурное-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page

Scenario: Average price is changed in 1 days

Given there is the product with 'Печенье Астраханское' name, 'Печенье-Астраханское-Артикул' sku, '45695514566' barcode, 'liter' units, '15' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD8' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Астраханское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '26' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD8' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Астраханское-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Астраханское-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD9' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-1day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Астраханское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '56' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD9' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Астраханское-Артикул' sku has 'amounts purchasePrice' element equal to '56' on amounts page
And the user checks the product with 'Печенье-Астраханское-Артикул' sku has 'amounts averagePrice' element equal to '41' on amounts page

Scenario: Average price round checking

Given there is the product with 'Печенье Киевское' name, 'Печенье-Киевское-Артикул' sku, '45695514566' barcode, 'liter' units, '15' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD10' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Киевское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '23,33' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD10' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Киевское-Артикул' sku has 'amounts purchasePrice' element equal to '23,33' on amounts page
And the user checks the product with 'Печенье-Киевское-Артикул' sku has 'amounts averagePrice' element equal to '23,33' on amounts page
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'AVPINCICD11' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-1day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Киевское' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '26' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'AVPINCICD11' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Киевское-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Киевское-Артикул' sku has 'amounts averagePrice' element equal to '24,67' on amounts page

Scenario: Average price calculation

Given there is the product with 'Печенье Юбилейное' name, 'Печенье-Юбилейное-Артикул' sku, '45695514566' barcode, 'liter' units, '25,50' purchasePrice
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Приемка-1' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Юбилейное' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '26' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Приемка-1' sku is present
When the user clicks the create button on the invoice list page
And the user inputs 'Приемка-2' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-10day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Юбилейное' in the invoice product 'productName' field
When the user inputs '5' in the invoice product 'productAmount' field
And the user inputs '29' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Приемка-2' sku is present
When the user clicks the create button on the invoice list page
And the user inputs 'Приемка-3' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-4day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'Печенье Юбилейное' in the invoice product 'productName' field
When the user inputs '10' in the invoice product 'productAmount' field
And the user inputs '31' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Приемка-1' sku is present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts purchasePrice' element equal to '31' on amounts page
And the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts averagePrice' element equal to '28,6' on amounts page
Given the user is on the invoice list page
When the user open the invoice card with 'Приемка-3' sku
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is present
When the user clicks edit button and starts invoice edition
And the user deletes the invoice product with 'Печенье-Юбилейное-Артикул' sku
And the user clicks OK and accepts deletion
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is not present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is not present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts purchasePrice' element equal to '29' on amounts page
And the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts averagePrice' element equal to '27' on amounts page
Given the user is on the invoice list page
When the user open the invoice card with 'Приемка-2' sku
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is present
When the user clicks edit button and starts invoice edition
And the user deletes the invoice product with 'Печенье-Юбилейное-Артикул' sku
And the user clicks OK and accepts deletion
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is not present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is not present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts purchasePrice' element equal to '26' on amounts page
And the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts averagePrice' element equal to '26' on amounts page
Given the user is on the invoice list page
When the user open the invoice card with 'Приемка-1' sku
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is present
When the user clicks edit button and starts invoice edition
And the user deletes the invoice product with 'Печенье-Юбилейное-Артикул' sku
And the user clicks OK and accepts deletion
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is not present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'Печенье-Юбилейное-Артикул' sku is not present
Given the user runs the recalculate_metrics cap command
Given the user opens amount list page
Then the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts purchasePrice' element equal to '25,50' on amounts page
And the user checks the product with 'Печенье-Юбилейное-Артикул' sku has 'amounts averagePrice' element equal to '—' on amounts page
10.1 Удаление товара из накладной при редактировании

Narrative:

Как заведующим отделом,
Я хочу удалть товарные позиции из накладной,
Чтобы исправлять в ней ошибки

Meta:
@sprint 7
@us 10.1

Scenario: Invoice product deletion

Given there is the invoice 'InvoiceProductdeletion-1' with product 'IE-IPD' name, 'IE-IPD' sku, 'IE-IPD' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProductdeletion-1' sku
Then the user checks the invoice product with 'IE-IPD' sku is present
When the user clicks edit button and starts invoice edition
And the user deletes the invoice product with 'IE-IPD' sku
And the user clicks OK and accepts deletion
Then the user checks the invoice product with 'IE-IPD' sku is not present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'IE-IPD' sku is not present

Scenario:Invoice product deletion cancel

Given there is the invoice 'InvoiceProductdeletion-3' with product 'IE-IPD' name, 'IE-IPD' sku, 'IE-IPD' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProductdeletion-3' sku
Then the user checks the invoice product with 'IE-IPD' sku is present
When the user clicks edit button and starts invoice edition
And the user deletes the invoice product with 'IE-IPD' sku
And the user clicks Cancel and discard deletion
Then the user checks the invoice product with 'IE-IPD' sku is present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'IE-IPD' sku is present

Scenario: Invoice product adding and deletion

Given there is the invoice with 'InvoiceProductdeletion-4' sku
And the user logs in as 'departmentManager'
And there is the product with 'IE-IPD' name, 'IE-IPD' sku, 'IE-IPD' barcode, 'liter' units
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProductdeletion-4' sku
And the user clicks edit button and starts invoice edition
And the user inputs 'IE-IPD' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '3' in the invoice product 'invoiceCost' field
And the user clicks the add invoice product button and adds the invoice product
And the user deletes the invoice product with 'IE-IPD' sku
And the user clicks OK and accepts deletion
Then the user checks the invoice product with 'IE-IPD' sku is not present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'IE-IPD' sku is not present

Scenario: Checking amountlist after invoice product deletion

Given there is the invoice with 'InvoiceProductdeletion-2' sku
And the user logs in as 'departmentManager'
And there is the product with 'IE-IPD-AM' name, 'IE-IPD-AM' sku, 'IE-IPD-AM' barcode, 'liter' units
Given the user opens amount list page
Then the user checks the product with 'IE-IPD-AM' sku has 'amount' equal to '0' on amounts page
Given the user is on the invoice list page
When the user open the invoice card with 'InvoiceProductdeletion-2' sku
And the user clicks edit button and starts invoice edition
And the user inputs 'IE-IPD-AM' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '3' in the invoice product 'invoiceCost' field
And the user clicks the add invoice product button and adds the invoice product
Then the user checks the invoice product with 'IE-IPD-AM' sku is present
When the user clicks finish edit button and ends the invoice edition
Then the user checks the invoice product with 'IE-IPD-AM' sku is present
Given the user opens amount list page
Then the user checks the product with 'IE-IPD-AM' sku has 'amount' equal to '1' on amounts page
Given the user is on the invoice list page
When the user open the invoice card with 'InvoiceProductdeletion-2' sku
And the user clicks edit button and starts invoice edition
And the user deletes the invoice product with 'IE-IPD-AM' sku
And the user clicks OK and accepts deletion
When the user clicks finish edit button and ends the invoice edition
Given the user opens amount list page
Then the user checks the product with 'IE-IPD-AM' sku has 'amount' equal to '0' on amounts page

Scenario: Checks users cant delete product invoice in not edit mode - regress

Given there is the invoice 'InvoiceProductdeletion-6' with product 'IE-IPD' name, 'IE-IPD' sku, 'IE-IPD' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProductdeletion-6' sku
And the user try to delete the invoice product with 'IE-IPD' sku

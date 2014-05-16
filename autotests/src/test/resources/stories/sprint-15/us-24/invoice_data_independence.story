Meta:
@sprint_15
@us_24

Narrative:
As заведующим отделом,
I want to чтобы данные в накладной не изменялись при редактировании коммерческой службой товаров
In order to иметь возможность работать с оригинальной версией документа

Scenario: Invoice data independence

Given there is the invoice 'Invoice-DI-Test' with product 'Invoice-DI-Test name' name, 'Invoice-DI-Test sku' sku, 'Invoice-DI-Test barcode' barcode, 'kg' units
And the user navigates to the product with sku 'Invoice-DI-Test sku'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| name | Invoice-DI-Test name edited |
| barcode | Invoice-DI-Test barcode edited |
| sku | Invoice-DI-Test sku edited |
And the user clicks the create button
Then the user checks the stored input values
When the user logs out
Given the user navigates to the invoice page with name 'Invoice-DI-Test'
And the user logs in as 'departmentManager'
Then the user checks the product with 'Invoice-DI-Test sku edited' sku is not present
And the user checks the product with 'Invoice-DI-Test sku' sku has values
| elementName | value |
| productName | Invoice-DI-Test name |
| productBarcode | Invoice-DI-Test barcode |
| productSku | Invoice-DI-Test sku |

Scenario: Edited product can be added to invoice

Given there is the invoice 'Invoice-DI-Test1' with product 'Invoice-DI-Test1 name' name, 'Invoice-DI-Test1 sku' sku, 'Invoice-DI-Test1 barcode' barcode, 'kg' units
And the user navigates to the product with sku 'Invoice-DI-Test1 sku'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| name | Invoice-DI-Test1 name edited |
| barcode | Invoice-DI-Test1 barcode edited |
| sku | Invoice-DI-Test1 sku edited |
And the user clicks the create button
Then the user checks the stored input values
When the user logs out
Given the user navigates to the invoice page with name 'Invoice-DI-Test1'
And the user logs in as 'departmentManager'
When the user inputs 'Invoice-DI-Test1 name edited' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Then the user checks the product with 'Invoice-DI-Test1 sku edited' sku has values
| elementName | value |
| productName | Invoice-DI-Test1 name edited |
| productBarcode | Invoice-DI-Test1 barcode edited |
| productSku | Invoice-DI-Test1 sku edited |

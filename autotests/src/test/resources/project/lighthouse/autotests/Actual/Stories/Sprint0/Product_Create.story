Create new product story

Narrative:
In order to add new product to stock
As a sales manager
I want to create new products

Scenario: Creating new product 1
Given the user is on the order create page
When the user inputs 'Наименование1' in 'name' field
And the user inputs 'Производитель1' in 'vendor' field
And the user inputs 'Россия1' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '123456' in 'sku' field
And the user inputs 'Info1' in 'info' field
And the user clicks the create button

Scenario: Creating new product 2
Given the user is on the order create page
When the user inputs 'Наименование2' in 'name' field
And the user inputs 'Производитель2' in 'vendor' field
And the user inputs 'Россия2' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'liter' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user inputs '123456' in 'sku' field
And the user inputs 'Info2' in 'info' field
And the user clicks the create button

Scenario: Creating new product 3
Given the user is on the order create page
When the user inputs 'Наименование3' in 'name' field
And the user inputs 'Производитель3' in 'vendor' field
And the user inputs 'Россия' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'kg' in 'unit' dropdown
And the user selects '18' in 'vat' dropdown
And the user inputs '123456' in 'sku' field
And the user inputs 'Info3' in 'info' field
And the user clicks the create button
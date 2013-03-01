Create new order story

Narrative:
In order to add new order to stock
As a sales manager
I want to create new orders

Scenario: Creating new order
Given the user is on the order create page
When the user inputs 'name1' in 'name' field
And the user inputs 'Country' in 'vendor' field
And the user inputs 'Россия' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects 'liter' in 'unit' dropdown
And the user selects 'kg' in 'unit' dropdown
And the user selects '1' in 'vat' dropdown
And the user selects '5' in 'vat' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '123456' in 'sku' field
And the user inputs 'Info' in 'info' field
And the user clicks the create button
Scenario: Creating new order
Given the user is on the order edit page
When the user inputs 'Наименование' in 'name' field
When the user inputs 'Yfbvtyjdfy' in 'vendor' field
When the user inputs 'Yfbvtyjdfy' in 'vendorCountry' field
When the user inputs 'Yfbvtyjdfy' in 'purchasePrice' field
When the user inputs 'Yfbvtyjdfy' in 'barcode' field
When the user selects '$value' in 'unit' dropdown
When the user selects '$value' in 'vat' dropdown
When the user inputs 'Yfbvtyjdfy' in 'sku' field
When the user inputs 'Yfbvtyjdfy' in 'info' field
When the user clicks the create button
Then the user checks is all good

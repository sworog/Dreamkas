Scenario: Editing Order Ok
Given the user is on the order edit page
When the user edits 'Наименование' in 'name' field
When the user edits 'Yfbvtyjdfy' in 'vendor' field
When the user edits 'Yfbvtyjdfy' in 'vendorCountry' field
When the user edits 'Yfbvtyjdfy' in 'purchasePrice' field
When the user edits 'Yfbvtyjdfy' in 'barcode' field
When the user selects '$value' in 'unit' dropdown
When the user selects '$value' in 'vat' dropdown
When the user edits 'Yfbvtyjdfy' in 'sku' field
When the user edits 'Yfbvtyjdfy' in 'info' field
When the user clicks the cancel button
Then the user checks is all good

Scenario: Editing Order Cancel
Given the user is on the order edit page
When the user edits 'Наименование' in 'name' field
When the user edits 'Yfbvtyjdfy' in 'vendor' field
When the user edits 'Yfbvtyjdfy' in 'vendorCountry' field
When the user edits 'Yfbvtyjdfy' in 'purchasePrice' field
When the user edits 'Yfbvtyjdfy' in 'barcode' field
When the user selects '$value' in 'unit' dropdown
When the user selects '$value' in 'vat' dropdown
When the user edits 'Yfbvtyjdfy' in 'sku' field
When the user edits 'Yfbvtyjdfy' in 'info' field
When the user clicks the edit button
Then the user checks is all good
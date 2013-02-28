Scenario: Editing Order Ok
Given the user is on the order edit page
When the user edits 'Наименование23' in 'name' field
When the user edits 'Кефиромания' in 'vendor' field
When the user edits 'Страна' in 'vendorCountry' field
When the user edits '350' in 'purchasePrice' field
When the user edits '456456' in 'barcode' field
When the user selects 'unit' in 'unit' dropdown
When the user selects 'liter' in 'unit' dropdown
When the user selects 'kg' in 'unit' dropdown
When the user selects '1' in 'vat' dropdown
When the user selects '5' in 'vat' dropdown
When the user selects '10' in 'vat' dropdown
When the user edits '454545' in 'sku' field
When the user edits 'апапапап' in 'info' field
When the user clicks the cancel button
Then the user checks is all good

Scenario: Editing Order Cancel
Given the user is on the order edit page
When the user edits 'Наименование23' in 'name' field
When the user edits 'Кефиромания' in 'vendor' field
When the user edits 'Страна' in 'vendorCountry' field
When the user edits '350' in 'purchasePrice' field
When the user edits '456456' in 'barcode' field
When the user selects 'unit' in 'unit' dropdown
When the user selects 'liter' in 'unit' dropdown
When the user selects 'kg' in 'unit' dropdown
When the user selects '1' in 'vat' dropdown
When the user selects '5' in 'vat' dropdown
When the user selects '10' in 'vat' dropdown
When the user edits '454545' in 'sku' field
When the user edits 'апапапап' in 'info' field
When the user clicks the edit button
Then the user checks is all good
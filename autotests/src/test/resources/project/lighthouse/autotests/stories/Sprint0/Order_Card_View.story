Scenario: Viewing Order Card after Creation
Given the user is on the order edit page
When the user inputs 'Наименование' in 'name' field
When the user inputs 'Yfbvtyjdfy' in 'vendor' field
When the user inputs 'Yfbvtyjdfy' in 'vendorCountry' field
When the user inputs 'Yfbvtyjdfy' in 'purchasePrice' field
When the user inputs 'Yfbvtyjdfy' in 'barcode' field
When the user selects 'unit' in 'unit' dropdown
When the user selects 'liter' in 'unit' dropdown
When the user selects 'kg' in 'unit' dropdown
When the user selects '1' in 'vat' dropdown
When the user selects '5' in 'vat' dropdown
When the user selects '10' in 'vat' dropdown
When the user inputs 'Yfbvtyjdfy' in 'sku' field
When the user inputs 'Yfbvtyjdfy' in 'info' field
When the user clicks the create button
Then the user checks is all good
Given the user is on the order card view
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'



Scenario: Viewing Order Card after Edition OK
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
Given the user is on the order card view
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'

Scenario: Viewing Order Card after Edition Cancel
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
Given the user is on the order card view
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
Then the user checks 'elementName' value is 'expectedValue'
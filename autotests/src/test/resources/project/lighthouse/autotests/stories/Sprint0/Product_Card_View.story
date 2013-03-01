View product cards story

Narrative:
In order to view product cards in stock
As a sales manager
I want to view product card

Scenario: Viewing product Card after Creation
Given the user is on the order edit page
When the user inputs 'Наименование' in 'name' field
And the user inputs 'Yfbvtyjdfy' in 'vendor' field
And the user inputs 'Yfbvtyjdfy' in 'vendorCountry' field
And the user inputs 'Yfbvtyjdfy' in 'purchasePrice' field
And the user inputs 'Yfbvtyjdfy' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects 'liter' in 'unit' dropdown
And the user selects 'kg' in 'unit' dropdown
And the user selects '1' in 'vat' dropdown
And the user selects '5' in 'vat' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Yfbvtyjdfy' in 'sku' field
And the user inputs 'Yfbvtyjdfy' in 'info' field
And the user clicks the create button
Then the user checks is all good
Given the user is on the order card view
Then the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'

Scenario: Viewing product Card after Edition OK
Given the user is on the order edit page
When the user edits 'Наименование23' in 'name' field
And the user edits 'Кефиромания' in 'vendor' field
And the user edits 'Страна' in 'vendorCountry' field
And the user edits '350' in 'purchasePrice' field
And the user edits '456456' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects 'liter' in 'unit' dropdown
And the user selects 'kg' in 'unit' dropdown
And the user selects '1' in 'vat' dropdown
And the user selects '5' in 'vat' dropdown
And the user selects '10' in 'vat' dropdown
And the user edits '454545' in 'sku' field
And the user edits 'апапапап' in 'info' field
And the user clicks the cancel button
Then the user checks is all good
Given the user is on the order card view
Then the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'

Scenario: Viewing product Card after Edition Cancel
Given the user is on the order edit page
When the user edits 'Наименование23' in 'name' field
And the user edits 'Кефиромания' in 'vendor' field
And the user edits 'Страна' in 'vendorCountry' field
And the user edits '350' in 'purchasePrice' field
And the user edits '456456' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects 'liter' in 'unit' dropdown
And the user selects 'kg' in 'unit' dropdown
And the user selects '1' in 'vat' dropdown
And the user selects '5' in 'vat' dropdown
And the user selects '10' in 'vat' dropdown
And the user edits '454545' in 'sku' field
And the user edits 'апапапап' in 'info' field
And the user clicks the edit button
Then the user checks is all good
Given the user is on the order card view
Then the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
And the user checks 'elementName' value is 'expectedValue'
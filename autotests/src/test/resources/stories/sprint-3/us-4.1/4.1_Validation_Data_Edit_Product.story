Meta:
@sprint 3
@us 4.1

Scenario: Edit product validation - Field length validation

Given there is created product with sku 'ED-NMLV'
And the user navigates to the product with sku 'ED-NMLV'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user generates charData with <charNumber> number in the <elementName> field
Then the user checks <elementName> field contains only <charNumber> symbols
When the user clicks the create button
Then the user sees no error messages

Examples:
| charNumber | elementName |
| 300 | name |
| 200 | barcode |
| 300 | vendor |
| 100 | vendorCountry |
| 2000 | info |

Scenario: Edit product validation - Field length validation negative

Given there is created product with sku 'ED-NMLVN'
And the user navigates to the product with sku 'ED-NMLVN'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user generates charData with <charNumber> number in the <elementName> field
Then the user checks <elementName> field contains only <charNumber> symbols
When the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| charNumber | elementName | errorMessage |
| 301 | name | Не более 300 символов |
| 201 | barcode | Не более 200 символов |
| 101 | sku | Не более 100 символов |
| 301 | vendor | Не более 300 символов |
| 101 | vendorCountry | Не более 100 символов |
| 2001 | info | Не более 2000 символов |

Scenario: Edit product validation - Name field is required

Given there is created product with sku 'ED-NFIR'
And the user navigates to the product with sku 'ED-NFIR'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |

Examples:
| inputText | elementName |
|  | name |
|  | sku |

Scenario: Edit product validation - Sku field validation good

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'Sku field validation good' in 'name' field
And the user inputs '1001DS8' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Given there is created product with sku 'ED-SKVG'
And the user is on the product list page
When the user open the product card with 'ED-SKVG' sku
And the user clicks the edit button on product card view page
And the user inputs 'Sku field validation good' in 'name' field
And the user inputs '1001DS8' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Такой артикул уже есть |

Scenario: Edit product validation - Vendor,Barcode,VendorCountryInfo fields are not required

Given there is created product with sku 'ED-VBVCF'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-VBVCF' sku
And the user clicks the edit button on product card view page
And the user inputs 'Vendor,Barcode,VendorCountryInfo fields are not required' in 'name' field
And the user inputs 'EPVBVCF678' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPVBVCF678' sku is present

Scenario: Edit product validation - Purchase price validation good

Given there is created product with sku 'ED-PPVC'
And the user navigates to the product with sku 'ED-PPVC'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages
And the user checks the <elementName> value is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| ,78 | purchasePrice | 0,78 |
| .78 | purchasePrice | 0,78 |
| 123.25 | purchasePrice | 123,25 |
| 123,26 | purchasePrice | 123,26 |
| 789,6 | purchasePrice | 789,6 |
| 739,67 | purchasePrice | 739,67 |
| 10000000 | purchasePrice | 10 000 000,00 |

Scenario: Edit product validation - Purchase price validation negative

Given there is created product with sku 'ED-PPC3D'
And the user navigates to the product with sku 'ED-PPC3D'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
| 739,678 | purchasePrice | Цена не должна содержать больше 2 цифр после запятой |
| -152 | purchasePrice | Цена не должна быть меньше или равна нулю |
| -1 | purchasePrice | Цена не должна быть меньше или равна нулю |
| 0 | purchasePrice | Цена не должна быть меньше или равна нулю |
| BIG PRICE | purchasePrice | Цена не должна быть меньше или равна нулю |
| big price | purchasePrice | Цена не должна быть меньше или равна нулю |
| БОЛЬШАЯ ЦЕНА | purchasePrice | Цена не должна быть меньше или равна нулю |
| большая цена | purchasePrice | Цена не должна быть меньше или равна нулю |
| !@#$%^&*() | purchasePrice | Цена не должна быть меньше или равна нулю |
| %^#$Fgbdf345) | purchasePrice | Цена не должна быть меньше или равна нулю |
| 10000001 | purchasePrice | Цена не должна быть больше 10000000 |

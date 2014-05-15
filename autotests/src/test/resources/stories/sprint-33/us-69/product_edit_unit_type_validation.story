Meta:
@sprint_33
@us_69
@s33u69s06

Scenario: Edit product validation - Field length validation

Given there is created product with name 'ED-NMLV'
And the user navigates to the product with name 'ED-NMLV'
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

Scenario: Edit product validation - Field length validation negative

Given there is created product with name 'ED-NMLVN'
And the user navigates to the product with name 'ED-NMLVN'
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
| 301 | vendor | Не более 300 символов |
| 101 | vendorCountry | Не более 100 символов |

Scenario: Edit product validation - Name field is required

Given there is created product with name 'ED-NFIR'
And the user navigates to the product with name 'ED-NFIR'
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

Scenario: Edit product validation - Vendor,Barcode,VendorCountryInfo fields are not required

Given there is created product with name 'ED-VBVCF'
And the user navigates to the product with name 'ED-VBVCF'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs 'EPVBVCF678' in 'name' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPVBVCF678' name is present

Scenario: Edit product validation - Purchase price validation good

Given there is created product with name 'ED-PPVC'
And the user navigates to the product with name 'ED-PPVC'
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

Given there is created product with name 'ED-PPC3D'
And the user navigates to the product with name 'ED-PPC3D'
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
| BIG PRICE | purchasePrice | Значение должно быть числом |
| big price | purchasePrice | Значение должно быть числом |
| БОЛЬШАЯ ЦЕНА | purchasePrice | Значение должно быть числом |
| большая цена | purchasePrice | Значение должно быть числом |
| !@#$%^&*() | purchasePrice | Значение должно быть числом |
| %^#$Fgbdf345) | purchasePrice | Значение должно быть числом |
| 10000001 | purchasePrice | Цена не должна быть больше 10000000 |

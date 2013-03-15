
Scenario: Purchase price field is required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Unit fiels is required' in 'name' field
And the user inputs 'IFV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Purchase price validation sub zero
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-01' in 'name' field
And the user inputs 'PPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '-152' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purhase prise validation zero
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-02' in 'name' field
And the user inputs 'PPV-02' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '0' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation String en
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-03' in 'name' field
And the user inputs 'PPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Big price' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation String rus
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-04' in 'name' field
And the user inputs 'PPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Большая цена' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation symbols
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-05' in 'name' field
And the user inputs 'PPV-05' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '!@#$%^&*()' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation String+Symbols+Num
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-06' in 'name' field
And the user inputs 'PPV-06' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '%^#$Fgbdf345)' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation commma
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-07' in 'name' field
And the user inputs 'PPV-07' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs ',78' in 'purchasePrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'PPV-07' sku has 'purchasePrice' equal to '0,78'

Scenario: Purchase price validation dott
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-08' in 'name' field
And the user inputs 'PPV-08' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs ',78' in 'purchasePrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'PPV-08' sku has 'purchasePrice' equal to '0,78'

Scenario: Purchase price validation comma
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price comma' in 'name' field
And the user inputs 'JFGE89075' in 'sku' field
And the user inputs '123.25' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'JFGE89075' sku has 'purchasePrice' equal to '123,25'

Scenario: Purchase price validation dot
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price dot' in 'name' field
And the user inputs 'JFGE89078' in 'sku' field
And the user inputs '125,26' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'JFGE89078' sku has 'purchasePrice' equal to '125,26'

Scenario: Purchase price validation one digit
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price one digit' in 'name' field
And the user inputs 'FTY64' in 'sku' field
And the user inputs '789,6' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user checks that he is on the 'ProductListPage'
And the user checks the product with 'FTY64' sku has 'purchasePrice' equal to '789,6'

Scenario: Purchase price validation two digits
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price two digits' in 'name' field
And the user inputs 'FTY645' in 'sku' field
And the user inputs '739,67' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user checks that he is on the 'ProductListPage'
And the user checks the product with 'FTY645' sku has 'purchasePrice' equal to '739,67'

Scenario: Purchase price validation three digits
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price three digits' in 'name' field
And the user inputs 'FTY6456' in 'sku' field
And the user inputs '739,678' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |
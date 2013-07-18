Meta:
@sprint 7
@us 12

Scenario: Retail price validation String+Symbols+Num
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-06' in 'name' field
And the user inputs 'RP-PPV-06' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '%^#$Fgbdf345)' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation commma
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-07' in 'name' field
And the user inputs 'RP-PPV-07' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs ',78' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation dott
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-08' in 'name' field
And the user inputs 'RP-PPV-08' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs ',78' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation comma
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'purchase price comma' in 'name' field
And the user inputs 'RP-JFGE89075' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '123.25' in 'retailPrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation dot
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'purchase price dot' in 'name' field
And the user inputs 'RP-JFGE89078' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '125,26' in 'retailPrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation one digit
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'purchase price one digit' in 'name' field
And the user inputs 'RP-FTY64' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '789,6' in 'retailPrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation two digits
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'purchase price two digits' in 'name' field
And the user inputs 'RP-FTY645' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '739,67' in 'retailPrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation three digits
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'purchase price three digits' in 'name' field
And the user inputs 'RP-FTY6456' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '739,678' in 'retailPrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |
When the user logs out

Scenario: Retail price validation sub zero
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-01' in 'name' field
And the user inputs 'RP-PPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '-152' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Purhase prise validation zero
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-02' in 'name' field
And the user inputs 'RP-PPV-02' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '0' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation String en small register
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-03' in 'name' field
And the user inputs 'RP-PPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'big price' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation String en big register
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-03' in 'name' field
And the user inputs 'RP-PPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'BIG PRICE' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation String rus small register
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-04' in 'name' field
And the user inputs 'RP-PPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'большая цена' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation String rus big register
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-04' in 'name' field
And the user inputs 'RP-PPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'БОЛЬЩАЯ ЦЕНА' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation symbols
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-05' in 'name' field
And the user inputs 'RP-PPV-05' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '!@#$%^&*()' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Retail price validation length good
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-090' in 'name' field
And the user inputs 'RP-PV-090' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '10000000' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Retail price validation length negative
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-0941' in 'name' field
And the user inputs 'RP-PPV-0941' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '10000001' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |
When the user logs out

Scenario: Retail Price regress
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'RP-PPV-09412' in 'name' field
And the user inputs 'RP-PPV-09412' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'ssdsd' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
Then the user sees no error messages
| error message |
| Наценка должна быть больше -100% |
When the user logs out


Scenario: Retail price validation String+Symbols+Num
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '%^#$Fgbdf345)' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation commma
Given there is created product with sku 'ED-MVC-VCC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-VCC' sku
And the user clicks the edit button on product card view page
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs ',78' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation dott
Given there is created product with sku 'ED-MVC-VDT' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-VDT' sku
And the user clicks the edit button on product card view page
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs ',78' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation comma
Given there is created product with sku 'ED-MVC-VC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-VC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation dot
Given there is created product with sku 'ED-MVC-VD' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-VD' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '125,26' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation one digit
Given there is created product with sku 'ED-MVC-RPOD' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-RPOD' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '789,6' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation two digits
Given there is created product with sku 'ED-MVC-RPTW' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-RPTW' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '739,67' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation three digits
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '739,678' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |

Scenario: Retail price validation sub zero
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '-152' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purhase prise validation zero
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '0' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation String en small register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'big price' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation String en big register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'BIG PRICE' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation String rus small register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'большая цена' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation String rus big register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'БОЛЬЩАЯ ЦЕНА' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation symbols
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '!@#$%^&*()' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail price validation length good
Given there is created product with sku 'ED-MVC-RPVLG' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC-RPVLG' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '10000000' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail price validation length negative
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '10000001' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |

Scenario: Retail Price regress
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs 'ssdsd' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
Then the user sees no error messages
| error message |
| Наценка должна быть больше -100% |
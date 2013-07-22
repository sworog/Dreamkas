Meta:
@sprint 7
@us 12

Scenario: Edit Retail Markup validation sub zero -105
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs '-105' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Наценка должна быть больше -100% |
When the user logs out

Scenario: Edit Retail Markup validation sub zero -100
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs '-100' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Наценка должна быть больше -100% |
When the user logs out

Scenario: Edit Retail Markup validation sub zero -99
Given there is created product with sku 'ED-MVC-99' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC-99' sku
And the user clicks the edit button on product card view page
And the user inputs '-99' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Edit Retail Markup validation sub zero -99.99
Given there is created product with sku 'ED-MVC-99' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC-99' sku
And the user clicks the edit button on product card view page
And the user inputs '-99.99' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit Retail Markup validation zero
Given there is created product with sku 'ED-MVC-0' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC-0' sku
And the user clicks the edit button on product card view page
And the user inputs '0' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Edit Retail Markup validation one digit
Given there is created product with sku 'ED-MVC-OD' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC-OD' sku
And the user clicks the edit button on product card view page
And the user inputs '10,6' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Edit Retail Markup validation two digits
Given there is created product with sku 'ED-MVC-TD' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC-TD' sku
And the user clicks the edit button on product card view page
And the user inputs '12,67' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages
When the user logs out

Scenario: Edit Retail Markup validation three digits
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs '12,678' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение не должно содержать больше 2 цифр после запятой |
When the user logs out

Scenario: Edit Retail Markup validation String en small register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs 'big price' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |
When the user logs out

Scenario: Edit Retail Markup validation String en big register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs 'BIG PRICE' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |
When the user logs out

Scenario: Edit Retail Markup validation String rus small register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs 'большая цена' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |
When the user logs out

Scenario: Edit Retail Markup validation String rus big register
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs 'БОЛЬЩАЯ ЦЕНА' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |
When the user logs out

Scenario: Edit Retail Markup validation symbols
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs '!@#$%^&*()' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |
When the user logs out

Scenario: Edit Retail Markup regress
Given there is created product with sku 'ED-MVC' and '1' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-MVC' sku
And the user clicks the edit button on product card view page
And the user inputs '-100' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Наценка должна быть больше -100% |
Then the user sees no error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out
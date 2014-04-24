Meta:
@regression

Scenario: When writeOff is created the writeOff must be empty

Meta:
@regression
@r_id1

Given the user opens the write off create page
And the user logs in as 'departmentManager'

Then the user checks 'writeOff date' write off field contains only '0' symbols


Scenario: In the product edit price shows with the dot must be the comma

Meta:
@regression
@r_id2

Given there is the product with 'Regress-name' name, 'Regress-barcode' barcode, 'unit' units, '123,56' purchasePrice
And the user navigates to the product with sku 'Regress-sku'
And the user logs in as 'commercialManager'

When the user clicks the edit button on product card view page

Then the user checks the 'purchasePrice' value is '123,56'

Scenario: In the product edit cant save the form if the price above 1000 and price range is selected

Meta:
@regression
@r_id3

Given there is the subCategory with name 'defaultSubCategory-regress' related to group named 'defaultGroup-regress' and category named 'defaultCategory-regress'
And the user sets subCategory 'defaultSubCategory-regress' mark up with max '10' and min '0' values
And there is the product with 'Regress-name-1' name, 'Regress-barcode-1' barcode, 'unit' units, '12345,67' purchasePrice of group named 'defaultGroup-regress', category named 'defaultCategory-regress', subcategory named 'defaultSubCategory-regress'

And the user navigates to the product with sku 'Regress-sku-1'
And the user logs in as 'commercialManager'

When the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user clicks the create button

Then the user sees no error messages
| error message |
| Значение должно быть числом |
Then the user sees no error messages
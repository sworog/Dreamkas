Meta:
@sprint_17
@us_35

Narrative:
As a коммерческий директор,
I want to создавать новый товар в системе не указывая для него закупочную цену,
In order to чтобы запланировать товар в ассортимент еще до подписания договора с поставщиками

Scenario: Product creation with no purchasePrice

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs values in element fields
| elementName | value |
| name | PCWNPP |
| vat | 10 |
And the user clicks the create button
Then the user sees no error messages
And the user checks the products list contain product with name 'PCWNPP'

Scenario: Product with no prurchase price list checking

Given there is the product with 'ProductNoPriceName' name, 'ProductNoPriceBarCode' barcode, 'weight' type, '' purchasePrice
And the user is on the product list page
And the user logs in as 'commercialManager'

Then the user checks the product with name 'ProductNoPriceName' has purchasePrice equals to '—'

Scenario: Product with no purchase price card checking

Given there is the product with 'ProductNoPriceName' name, 'ProductNoPriceBarCode' barcode, 'weight' type, '' purchasePrice
And the user navigates to the product with name 'ProductNoPriceName'
And the user logs in as 'commercialManager'
Then the user checks the 'purchasePrice' value is 'отсутствует'

Scenario: If purchase price is not filled, mark up range fields are disabled

Given the user is on the product create page
And the user logs in as 'commercialManager'
Then the user checks 'retailMarkupMin' element is disabled
And the user checks 'retailMarkupMax' element is disabled

Scenario: If purchase price is not filled, retail price range fields are disabled

Given the user is on the product create page
And the user logs in as 'commercialManager'
Then the user checks 'retailPriceMin' element is disabled
And the user checks 'retailPriceMax' element is disabled

Scenario: Try to send form with data in mark up disabled fields

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs values in element fields
| elementName | value |
| name | PCWNPP1 |
| vat | 10 |
| purchasePrice | 1 |
| retailMarkupMin | 1 |
| retailMarkupMax | 2 |
| purchasePrice | |
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with name 'PCWNPP1' has purchasePrice equals to '—'

Scenario: Try to send form with data in retail price disabled fields

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs values in element fields
| elementName | value |
| purchasePrice | 1 |
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs values in element fields
| elementName | value |
| name | PCWNPP2 |
| vat | 10 |
| retailPriceMin | 1 |
| retailPriceMax | 2 |
| purchasePrice | |
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with name 'PCWNPP2' has purchasePrice equals to '—'

Scenario: WriteOff autocomplete search for product with no purchasePrice

Given skipped test
Given there is the product with 'ProductNoPriceName' name, 'ProductNoPriceBarCode' barcode, 'weight' type, '' purchasePrice
And there is the write off with number 'writeOffProductWithNoPrice'
And the user navigates to the write off with number 'writeOffProductWithNoPrice'
And the user logs in as 'departmentManager'
When the user clicks the edit button on product card view page
And the user inputs <value> in the write off <elementName>
Then the users checks no autocomplete results

Examples:
| value | elementName |
| ProductNoPriceName | writeOff product name autocomplete |
| ProductNoPriceSku | writeOff product sku autocomplete |
| ProductNoPriceBarCode | writeOff product barCode autocomplete |

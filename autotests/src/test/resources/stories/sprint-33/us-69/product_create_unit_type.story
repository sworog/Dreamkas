Создания штучного продукта

Narrative:
Как коммерческий директор,
Я хочу при добавлении штучного и весового товаров, ввести все необходимый данные
Чтобы ввести товар можно было продавать в магазине по всем правилам

Meta:
@sprint_33
@us_69
@product
@s33u69s01

Scenario: Create unit product 1

Meta:
@smoke
@s33u69s01e01

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Штучный'
And the user inputs 'ШтучныйТовар1' in 'name' field
And the user inputs 'Производитель1' in 'vendor' field
And the user inputs 'Россия1' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '10' in 'vat' dropdown
And the user clicks the create button

Then the user checks the products list contain product with name 'ШтучныйТовар1'

Scenario: Create unit product 2

Meta:
@smoke
@s33u69s01e02

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Штучный'
And the user inputs 'ШтучныйТовар2' in 'name' field
And the user inputs 'Производитель2' in 'vendor' field
And the user inputs 'Россия2' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '0' in 'vat' dropdown
And the user clicks the create button

Then the user checks the products list contain product with name 'ШтучныйТовар2'

Scenario: Create unit product 3

Meta:
@smoke
@s33u69s01e03

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Штучный'
And the user inputs 'ШтучныйТовар3' in 'name' field
And the user inputs 'Производитель3' in 'vendor' field
And the user inputs 'Россия' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '18' in 'vat' dropdown
And the user clicks the create button

Then the user checks the products list contain product with name 'ШтучныйТовар3'

Scenario: Verifying that there is no default values for vats

Meta:
@s33u69s01e04

Given the user is on the product create page
And the user logs in as 'commercialManager'
Then the user checks default value for 'vat' dropdown equal to ''

4 Создания продукта

Narrative:
Как коммерческий директор,
Я хочу создавать новый товар в системе,
Чтобы ввести товар в ассортимент

Meta:
@sprint_0
@us_4
@product

Scenario: Creating new product 1

Meta:
@smoke
@id_s0u4s1

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Наименование1' in 'name' field
And the user inputs 'Производитель1' in 'vendor' field
And the user inputs 'Россия1' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Info1' in 'info' field
And the user clicks the create button

Then the user checks the products list contain product with name 'Наименование1'

Scenario: Creating new product 2

Meta:
@smoke
@id_s0u4s2

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Наименование2' in 'name' field
And the user inputs 'Производитель2' in 'vendor' field
And the user inputs 'Россия2' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'liter' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user inputs 'Info2' in 'info' field
And the user clicks the create button

Then the user checks the products list contain product with name 'Наименование2'

Scenario: Creating new product 3

Meta:
@smoke
@id_s0u4s3

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Наименование3' in 'name' field
And the user inputs 'Производитель3' in 'vendor' field
And the user inputs 'Россия' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'kg' in 'unit' dropdown
And the user selects '18' in 'vat' dropdown
And the user inputs 'Info3' in 'info' field
And the user clicks the create button

Then the user checks the products list contain product with name 'Наименование3'

История просмотра списка товаров

Narrative:
Как комммерческий директор,
Я хочу просматривать список товаров,
Чтобы знать из каких товаров состоит ассортимент магазина

Scenario: Creating new product from product list
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs 'Производитель56' in 'vendor' field
And the user inputs 'Россия56' in 'vendorCountry' field
And the user inputs '12356' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1234567' in 'sku' field
And the user inputs 'Info1' in 'info' field
And the user clicks the create button
Then the user checks the product with '1234567' sku is present
Then the user checks the product with '1234567' sku has 'name' equal to 'Наименование56'
Then the user checks the product with '1234567' sku has 'vendor' equal to 'Производитель56'
Then the user checks the product with '1234567' sku has 'vendorCountry' equal to 'Россия56'
Then the user checks the product with '1234567' sku has 'purchasePrice' equal to '12356'

Scenario: Canceling creating new product page
Given the user is on the product list page
When the user creates new product from product list page
And the user clicks the cancel button
Then the user checks that he is on the 'ProductListPage'







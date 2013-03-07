View product cards story

Narrative:
In order to view product cards in stock
As a sales manager
I want to view product card

Scenario: Viewing product card after creation 1
Given the user is on the product list page
When the user creates new product from product list page
When the user inputs 'Веселый' in 'name' field
And the user inputs 'Рамзон' in 'vendor' field
And the user inputs 'Раша матушка' in 'vendorCountry' field
And the user inputs '5698' in 'purchasePrice' field
And the user inputs '8954' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '89745D' in 'sku' field
And the user inputs 'Доп инфо: Текст двадцать пять' in 'info' field
And the user clicks the create button
Then the user checks the product with '89745D' sku is present
When the user open the product card with '89745D' sku
Then the user checks the 'sku' value is '89745D'
And the user checks the 'name' value is 'Веселый'
And the user checks the 'vendor' value is 'Рамзон, Раша матушка'
And the user checks the 'purchasePrice' value is '5698'
And the user checks the 'barcode' value is '8954'
And the user checks the 'unit' value is 'unit'
And the user checks the 'vat' value is '10'
And the user checks the 'info' value is 'Доп инфо: Текст двадцать пять'


Scenario: Viewing product card after creation 2
Given the user is on the product list page
When the user creates new product from product list page
When the user inputs 'Веселый фермер' in 'name' field
And the user inputs 'Рамзон зона' in 'vendor' field
And the user inputs 'Раша матушка зе бест' in 'vendorCountry' field
And the user inputs '589554' in 'purchasePrice' field
And the user inputs '8988854' in 'barcode' field
And the user selects 'liter' in 'unit' dropdown
And the user selects '0' in 'vat' dropdown
And the user inputs '89745D' in 'sku' field
And the user inputs 'Доп инфо: Тестовые сущности' in 'info' field
And the user clicks the create button
Then the user checks the product with '89745D' sku is present
When the user open the product card with '89745D' sku
Then the user checks the 'sku' value is '89745D'
And the user checks the 'name' value is 'Веселый фермер'
And the user checks the 'vendor' value is 'Рамзон зона, Раша матушка зе бест'
And the user checks the 'purchasePrice' value is '589554'
And the user checks the 'barcode' value is '8988854'
And the user checks the 'unit' value is 'liter'
And the user checks the 'vat' value is '0'
And the user checks the 'info' value is 'Доп инфо: Тестовые сущности'

Scenario: Viewing product card after creation 3
Given the user is on the product list page
When the user creates new product from product list page
When the user inputs 'ООО ИМЯ' in 'name' field
And the user inputs 'Фирма 1' in 'vendor' field
And the user inputs 'Германия' in 'vendorCountry' field
And the user inputs '567' in 'purchasePrice' field
And the user inputs '0000000' in 'barcode' field
And the user selects 'kg' in 'unit' dropdown
And the user selects '18' in 'vat' dropdown
And the user inputs '89745D' in 'sku' field
And the user inputs 'Доп инфо: Тестовые сущности 3434' in 'info' field
And the user clicks the create button
Then the user checks the product with '89745D' sku is present
When the user open the product card with '89745D' sku
Then the user checks the 'sku' value is '89745D'
And the user checks the 'name' value is 'ООО ИМЯ'
And the user checks the 'vendor' value is 'Фирма 1, Германия'
And the user checks the 'purchasePrice' value is '567'
And the user checks the 'barcode' value is '0000000'
And the user checks the 'unit' value is 'kg'
And the user checks the 'vat' value is '18'
And the user checks the 'info' value is 'Доп инфо: Тестовые сущности 3434'



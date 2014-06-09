Просмотр карточки товара

Narrative:
Как коммерческий директор,
Я хочу просматривать карточку товара,
Чтобы определять насколько верные и полные данные там содержатся.

Meta:
@sprint_33
@us_69
@product
@s33u69s03

Scenario: View unit product card after create

Meta:
@smoke
@s33u69s03e01

Given the user is on the product list page
And the user logs in as 'owner'

When the user creates new product from product list page
And the user selects product type 'Штучный'
And the user inputs 'Веселый' in 'name' field
And the user inputs 'Рамзон' in 'vendor' field
And the user inputs 'Раша матушка' in 'vendorCountry' field
And the user inputs '5698' in 'purchasePrice' field
And the user inputs '8954' in 'barcode' field
And the user selects '10' in 'vat' dropdown
And the user clicks the create button

Then the user checks the products list contain product with name 'Веселый'

When the user clicks on product with name 'Веселый'

Then the user checks the 'name' value is 'Веселый'
And the user checks the 'type' value is 'Штучный'
And the user checks the 'vendor' value is 'Рамзон'
And the user checks the 'vendorCountry' value is 'Раша матушка'
And the user checks the 'purchasePrice' value is '5 698,00'
And the user checks the 'barcode' value is '8954'
And the user checks the 'units' value is 'Штуки'
And the user checks the 'vat' value is '10'

Scenario: View weight product card after create

Meta:
@smoke
@s33u69s03e02

Given the user is on the product list page
And the user logs in as 'owner'

When the user creates new product from product list page
And the user selects product type 'Весовой'
And the user inputs 'Веселый фермер' in 'name' field
And the user inputs 'Рамзон зона' in 'vendor' field
And the user inputs 'Раша матушка зе бест' in 'vendorCountry' field
And the user inputs '589554' in 'purchasePrice' field
And the user inputs '8988854' in 'barcode' field
And the user selects '0' in 'vat' dropdown
And the user inputs 'Веселый фермер весы' in 'nameOnScales' field
And the user inputs 'Веселый фермер весы описание' in 'descriptionOnScales' field
And the user inputs 'веселье, фермер' in 'ingredients' field
And the user inputs 'жиры: 40%' in 'nutritionFacts' field
And the user inputs '120' in 'shelfLife' field
And the user clicks the create button

Then the user checks the products list contain product with name 'Веселый фермер'

When the user clicks on product with name 'Веселый фермер'

Then the user checks the 'name' value is 'Веселый фермер'
And the user checks the 'type' value is 'Весовой'
And the user checks the 'vendor' value is 'Рамзон зона'
And the user checks the 'vendorCountry' value is 'Раша матушка зе бест'
And the user checks the 'purchasePrice' value is '589 554,00'
And the user checks the 'barcode' value is '8988854'
And the user checks the 'units' value is 'Килограмм'
And the user checks the 'vat' value is '0'
And the user checks the 'nameOnScales' value is 'Веселый фермер весы'
And the user checks the 'descriptionOnScales' value is 'Веселый фермер весы описание'
And the user checks the 'ingredients' value is 'веселье, фермер'
And the user checks the 'nutritionFacts' value is 'жиры: 40%'
And the user checks the 'shelfLife' value is '120'

Scenario: View alcohol product card after create (not implemented yet)

Meta:
@smoke
@s33u69s03e03

Given the user is on the product list page
And the user logs in as 'owner'

When the user creates new product from product list page
And the user inputs 'ООО ИМЯ' in 'name' field
And the user inputs 'Фирма 1' in 'vendor' field
And the user inputs 'Германия' in 'vendorCountry' field
And the user inputs '567' in 'purchasePrice' field
And the user inputs '0000000' in 'barcode' field
And the user selects '18' in 'vat' dropdown
And the user clicks the create button

Then the user checks the products list contain product with name 'ООО ИМЯ'

When the user clicks on product with name 'ООО ИМЯ'

Then the user checks the 'name' value is 'ООО ИМЯ'
And the user checks the 'type' value is 'Штучный'
And the user checks the 'vendor' value is 'Фирма 1'
And the user checks the 'vendorCountry' value is 'Германия'
And the user checks the 'purchasePrice' value is '567'
And the user checks the 'barcode' value is '0000000'
And the user checks the 'units' value is 'Штуки'
And the user checks the 'vat' value is '18'
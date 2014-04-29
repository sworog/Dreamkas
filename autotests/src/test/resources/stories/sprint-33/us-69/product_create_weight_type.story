Создание весового продукта

Narrative:
Как коммерческий директор,
Я хочу при добавлении штучного и весового товаров, ввести все необходимый данные
Чтобы ввести товар можно было продавать в магазине по всем правилам

Meta:
@sprint_33
@us_69
@product
@s33u69s02

Scenario: Create weight product 1

Meta:
@smoke
@s33u69s02e01

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Весовой'
And the user inputs 'ВесовойТовар1' in 'name' field
And the user inputs 'Производитель1' in 'vendor' field
And the user inputs 'Россия1' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '10' in 'vat' dropdown
And the user inputs 'ВесовойТовар1 название на весах' in 'nameOnScales' field
And the user inputs 'ВесовойТовар1 описание на весах' in 'descriptionOnScales' field
And the user inputs 'Состав1' in 'ingredients' field
And the user inputs 'Пищевая ценность1' in 'nutritionFacts' field
And the user inputs '1' in 'shelfTime' field
And the user clicks the create button

Then the user checks the products list contain product with name 'ВесовойТовар1'

Scenario: Creating weight product 2

Meta:
@smoke
@s33u69s02e02

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Весовой'
And the user inputs 'ВесовойТовар2' in 'name' field
And the user inputs 'Производитель1' in 'vendor' field
And the user inputs 'Россия1' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '10' in 'vat' dropdown
And the user inputs 'ВесовойТовар2 название на весах' in 'nameOnScales' field
And the user inputs 'ВесовойТовар2 описание на весах' in 'descriptionOnScales' field
And the user inputs 'Углеводы, Белки, Жиры' in 'ingredients' field
And the user clicks the create button

Then the user checks the products list contain product with name 'ВесовойТовар2'

Scenario: Creating weight product 3

Meta:
@smoke
@s33u69s02e03

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Весовой'
And the user inputs 'ВесовойТовар3' in 'name' field
And the user inputs 'Производитель3' in 'vendor' field
And the user inputs 'Россия3' in 'vendorCountry' field
And the user inputs '123' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '10' in 'vat' dropdown
And the user clicks the create button

Then the user checks the products list contain product with name 'ВесовойТовар3'

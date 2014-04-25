История просмотра списка товаров

Narrative:
Как комммерческий директор,
Я хочу просматривать список товаров,
Чтобы знать из каких товаров состоит ассортимент магазина

Meta:
@sprint_1
@us_2.1

Scenario: Creating new product from product list

Meta:
@smoke
@s1u2.1s1

Given the user is on the product list page
And the user logs in as 'commercialManager'

When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs 'Производитель56' in 'vendor' field
And the user inputs 'Россия56' in 'vendorCountry' field
And the user inputs '12356' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Info1' in 'info' field
And the user clicks the create button

Then the user checks product list contains values
| name | vendor | vendorCountry | purchasePrice |
| Наименование56 | Производитель56 | Россия56 | 12 356,00 р. |








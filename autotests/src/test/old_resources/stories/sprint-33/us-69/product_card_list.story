История просмотра списка товаров

Narrative:
Как комммерческий директор,
Я хочу просматривать список товаров,
Чтобы знать из каких товаров состоит ассортимент магазина

Meta:
@sprint_33
@us_69
@product
@s33u69s04

Scenario: Creating new product from product list

Meta:
@smoke
@s33u69s04e01

Given the user is on the product list page
And the user logs in as 'owner'

When the user creates new product from product list page
And the user selects product type 'Штучный'
And the user inputs 'Наименование56' in 'name' field
And the user inputs 'Производитель56' in 'vendor' field
And the user inputs 'Россия56' in 'vendorCountry' field
And the user inputs '12356' in 'purchasePrice' field
And the user inputs '123' in 'barcode' field
And the user selects '10' in 'vat' dropdown
And the user clicks the create button

Then the user checks product list contains values
| name | vendor | vendorCountry | purchasePrice |
| Наименование56 | Производитель56 | Россия56 | 12 356,00 р. |

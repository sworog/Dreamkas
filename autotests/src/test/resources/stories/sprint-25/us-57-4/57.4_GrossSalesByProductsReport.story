Meta:
@sprint 25
@us 57.4

Narrative:
As a директор магазина
I want to видеть сумму продаж каждого товара подкатегории на этот час в сравнении с суммой продаж на этот час вчера и и неделю назад
In order to вовремя понять какой товар подкатегории проваливает продажи

Scenario: Gross sale by products contains zero sales

Given there is the user with name 'storeManager-s25u574', position 'storeManager-s25u574', username 'storeManager-s25u574', password 'lighthouse', role 'storeManager'

Given there is the store with number '25574' managed by 'storeManager-s25u574'
And there is the subCategory with name 'defaultSubCategory-s25u574' related to group named 'defaultGroup-s25u574' and category named 'defaultCategory-s25u574'
And the user sets subCategory 'defaultSubCategory-s25u574' mark up with max '10' and min '0' values

Given there is the product with 'name-25574' name, '25574' sku, '25574' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u574', category named 'defaultCategory-s25u574', subcategory named 'defaultSubCategory-s25u574'

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link

When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'

Then the user checks the gross sale by products report contains zero sales

Scenario: Gross sale by products contains correct data
Scenario: Gross sale by products contains data red highlighted
Scenario: Gross sale by products can contains table product entry without barcode


Scenario: Чекать название столбцов????
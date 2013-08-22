Просмотр и создание товаров в крайнем узле классификатора

Narrative:
In order to вводимый в ассортимент товар сразу был создан в требуемой группе/категории/подкатегории
As a комммерческий директор,
I want to создать товар в крайнем узле классификатора,

Meta:
@sprint 14
@us 18

Scenario: SubCategory page full product create/edit

Meta:
@debug us18:1

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user fills form with following data
| elementName | value |
| name | Наименование1689 |
| vendor | Производитель1689 |
| vendorCountry | Россия1689 |
| purchasePrice | 1231689 |
| barcode | 1231689 |
| sku | 1689 |
| info | Info1689 |
| unit | unit |
| vat | 10 |
And the user clicks the create button
When the user open the product card with '1689' sku
Then the user checks the elements values
| elementName | value |
| name | Наименование1689 |
| vendor | Производитель1689 |
| vendorCountry | Россия1689 |
| purchasePrice | 1231689 |
| barcode | 1231689 |
| sku | 1689 |
| info | Info1689 |
| unit | штука |
| vat | 10 |
When the user clicks the edit button on product card view page
And the user fills form with following data
| elementName | value |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| purchasePrice | 8922174 |
| barcode | 102454 |
| sku | 89489545DGF2 |
| info | Info1689 |
| unit | liter |
| vat | 0 |
And the user clicks the create button
Then the user checks the elements values
| elementName | value |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| purchasePrice | 8922174 |
| barcode | 102454 |
| sku | 89489545DGF2 |
| info | Info1689 |
| unit | литр |
| vat | 0 |
When the user logs out

Scenario: Check product is related to group/category/subcategory

Meta:
@debug us18:2

Given there is the subCategory with name 'productListPage' related to group named 'productListPage' and category named 'productListPage'
And the user navigates to the subCategory 'productListPage', category 'productListPage', group 'productListPage' product list page
And there is the product with 'CPIRTGCS' name, 'CPIRTGCS' sku, 'CPIRTGCS' barcode, 'kg' units, '123' purchasePrice in the subcategory named 'productListPage'
And the user logs in as 'commercialManager'
When the user open the product card with 'CPIRTGCS' sku
Then the user checks the elements values
| elementName | value  |
| group | productListPage |
| category | productListPage |
| subCategory | productListPage |
When the user logs out

Scenario: Delete subcategory with products

Meta:
@debug us18:3

Given there is the subCategory with name 'productListPage' related to group named 'productListPage' and category named 'productListPage'
And there is the product with 'PWESIDS' name, 'PWESIDS' sku, 'PWESIDS' barcode, 'kg' units, '123' purchasePrice in the subcategory named 'productListPage'
And the user navigates to the subCategory 'productListPage', category 'productListPage', group 'productListPage' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of subCategory 'productListPage' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Подкатегория не пуста. Сначала удалите из нее все товары.'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Product create from triangle menu

!--Given there is the subCategory with name 'productListPage' related to group named 'productListPage' and category named 'productListPage'
!--And the user navigates to the subCategory 'productListPage', category 'productListPage', group 'productListPage' product list page
!--And the user logs in as 'commercialManager'
!--When the user clicks on start edition link and starts the edition
!--And the user opens pop up menu of subCategory 'productListPage' element
!--And the user creates new product through pop up menu
!--And the user inputs values in element fields
!--| elementName | value |
!--| name | PCFTM |
!--| vendor | PCFTM|
!--| vendorCountry | PCFTM |
!--| purchasePrice | 123 |
!--| sku | PCFTM-999 |
!--| unit | unit |
!--| vat | 10 |
!--And the user clicks the create button
!--Then the user checks the product with 'PCFTM-999' sku is present
!--When the user logs out

Scenario: No products dashboard link for commercial manager

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'products' section is not present
When the user logs out

Scenario: No products dashboard link for department manager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'products' section is not present
When the user logs out

Scenario: Catalog dashboard link is present for department manager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'catalog' section is present
When the user logs out

Scenario: Catalog - no edit link for department manager

Given the user opens catalog page
And the user logs in as 'departmentManager'
Then the user dont see the 403 error
And the user checks the edit button is not present
When the user logs out

Scenario: Catalog - products list view by department manager

!--Given there is the subCategory with name 'productListPage' related to group named 'productListPage' and category named 'productListPage'
!--And the user navigates to the subCategory 'productListPage', category 'productListPage', group 'productListPage' product list page
!--And there is the product with 'CPIRTGCS' name, 'CPIRTGCS' sku, 'CPIRTGCS' barcode, 'kg' units, '123' purchasePrice in the subcategory named 'productListPage'
!--And the user logs in as 'departmentManager'
!--When the user open the product card with 'CPIRTGCS' sku
!--Then the user checks the elements values
!--| elementName | expectedValue  |
!--| group | productListPage |
!--| category | productListPage |
!--| subCategory | productListPage |
!--When the user logs out

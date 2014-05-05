Просмотр и создание товаров в крайнем узле классификатора

Narrative:
In order to вводимый в ассортимент товар сразу был создан в требуемой группе/категории/подкатегории
As a комммерческий директор,
I want to создать товар в крайнем узле классификатора,

Meta:
@sprint_14
@us_18

Scenario: SubCategory page full product create/edit

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
| info | Info1689 |
| unit | unit |
| vat | 10 |
And the user clicks the create button

When the user clicks on product with name 'Наименование1689'

Then the user checks the elements values
| elementName | value |
| name | Наименование1689 |
| vendor | Производитель1689 |
| vendorCountry | Россия1689 |
| purchasePrice | 1 231 689,00 |
| barcode | 1231689 |
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
| info | Info1689 |
| unit | liter |
| vat | 0 |
And the user clicks the create button

Then the user checks the elements values
| elementName | value |
| name | Имя23 56 |
| vendor | Вендор45 |
| vendorCountry | Вендоркантри56 |
| purchasePrice | 8 922 174,00 |
| barcode | 102454 |
| info | Info1689 |
| unit | литр |
| vat | 0 |

Scenario: Check product is related to group/category/subcategory

Given there is the subCategory with name 'productListPage' related to group named 'productListPage' and category named 'productListPage'
And the user navigates to the subCategory 'productListPage', category 'productListPage', group 'productListPage' product list page
And there is the product with 'CPIRTGCS' name, 'CPIRTGCS' barcode, 'weight' type, '123' purchasePrice of group named 'productListPage', category named 'productListPage', subcategory named 'productListPage'
And the user logs in as 'commercialManager'
When the user clicks on product with name 'CPIRTGCS'
Then the user checks the elements values
| elementName | value  |
| group | productListPage |
| category | productListPage |
| subCategory | productListPage |

Scenario: Delete subcategory with products

Given there is the subCategory with name 'productListPage' related to group named 'productListPage' and category named 'productListPage'
And there is the product with 'PWESIDS' name, 'PWESIDS' barcode, 'weight' type, '123' purchasePrice of group named 'productListPage', category named 'productListPage', subcategory named 'productListPage'
And the user navigates to the subCategory 'productListPage', category 'productListPage', group 'productListPage' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of subCategory 'productListPage' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Подкатегория не пуста. Сначала удалите из нее все товары.'

Scenario: No products dashboard link for commercial manager

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the catalog navigation menu item is not visible

Scenario: No products dashboard link for department manager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the catalog navigation menu item is not visible

Scenario: Catalog dashboard link is present for department manager

Given before steps
And the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the catalog navigation menu item is visible

Scenario: Catalog - no edit link for department manager

Given before steps
And the user opens catalog page
And the user logs in as 'departmentManager'
Then the user dont see the 403 error
And the user checks the edit button is not present
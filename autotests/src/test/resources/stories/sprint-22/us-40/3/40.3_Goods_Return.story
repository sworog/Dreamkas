Meta:
@sprint 22
@us 40.3

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Returns checking

Meta:
@id s22u40.3t1
@description the balance of two products increase after product return is processed by system (subCategory product balance page, product returns page).

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-import-url | smb://faro.lighthouse.cs/centrum/reports |
| set10-import-login | erp |
| set10-import-interval | 60 |
| set10-import-password | erp |
And the user clicks import save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user logs out
Given there is the subCategory with name 'ProductsExportSubCategory' related to group named 'ProductsExportGroup' and category named 'ProductsExportCategory'
And the user sets subCategory 'ProductsExportSubCategory' mark up with max '30' and min '0' values
And there is the product with 'Профитроли Коппенрат&Вайс Бэйлис 280г' name, '1008577061437' sku, '1008577061437' barcode, 'unit' units, '20,80' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ReturnDepartmentManager', position 'ReturnDepartmentManager', username 'ReturnDepartmentManager', password 'lighthouse', role 'departmentManager'
And there is the user with name 'ReturnDepartmentManager2', position 'ReturnDepartmentManager2', username 'ReturnDepartmentManager2', password 'lighthouse', role 'departmentManager'
And there is the store with number '6666' managed by department manager named 'ReturnDepartmentManager'
And there is the store with number '7777' managed by department manager named 'ReturnDepartmentManager2'
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 1008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 0 | шт. | — | 20,80 р. |
When the user logs out
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager2' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 1008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 0 | шт. | — | 20,80 р. |
When the user logs out
Given the robot prepares import return data
And the robot waits the import folder become empty
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 1008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 2 | шт. | — | 20,80 р. |
Given the user navigates to the product with sku '1008577061437'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 2 | 26,99 | 53,98 |
When the user logs out
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager2' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 1008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 3 | шт. | — | 20,80 р. |
Given the user navigates to the product with sku '1008577061437'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 3 | 25,50 | 76,50 |

Scenario: No returns tab for storeManager

Meta:
@id s22u40.3t2
@description no product balance tab availabile for store manager

Given there is the user with name 'NRTF-1', position 'NRTF-1', username 'NRTF-1', password 'lighthouse', role 'storeManager'
And there is the store with number 'NRTF' managed by 'NRTF-1'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'NPBTF-name-1' name, 'SCPBC-sku-1' sku, 'SCPBC-barcode-1' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the product with sku 'SCPBC-sku-1'
When the user logs in using 'NRTF-1' userName and 'lighthouse' password
Then the user checks the local navigation return link is not visible

Scenario: No returns tab for commercialManager

Meta:
@id s22u40.3t3
@description no product balance tab availabile for commercial manager

Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'NPBTF-name-1' name, 'SCPBC-sku-1' sku, 'SCPBC-barcode-1' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the product with sku 'SCPBC-sku-1'
Given the user logs in as 'commercialManager'
Then the user checks the local navigation return link is not visible
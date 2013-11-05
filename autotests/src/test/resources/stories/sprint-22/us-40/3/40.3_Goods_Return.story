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
And there is the product with 'Профитроли Коппенрат&Вайс Бэйлис 280г' name, '4008577061437' sku, '4008577061437' barcode, 'unit' units, '20,80' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ProductsExportStoreManager', position 'ProductsExportStoreManager', username 'ProductsExportStoreManager', password 'lighthouse', role 'storeManager'
And there is the user with name 'ProductsExportStoreManager2', position 'ProductsExportStoreManager2', username 'ProductsExportStoreManager2', password 'lighthouse', role 'storeManager'
And there is the store with number '666' managed by 'ProductsExportStoreManager'
And there is the store with number '777' managed by 'ProductsExportStoreManager2'
And the user navigates to the product with sku '4008577061437'
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '26,99' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '26,99'
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 4008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 4008577061437 | 0 | шт. | — | 26,99 р. |
When the user logs out
Given the user navigates to the product with sku '4008577061437'
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '25,50' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '25,50'
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 4008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 4008577061437 | 0 | шт. | — | 25,50 |
When the user logs out
Given the robot prepares import return data
And the robot waits the import folder become empty
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 4008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 4008577061437 | 2 | шт. | — | 26,99 р. |
Given the user navigates to the product with sku '4008577061437'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 2013.10.03 | 2 | 26,99 | 53,98 |
When the user logs out
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| 4008577061437 | Профитроли Коппенрат&Вайс Бэйлис 280г | 4008577061437 | 3 | шт. | — | 25,50 |
Given the user navigates to the product with sku '4008577061437'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 2013.10.03 | 3 | 25,50 | 76,50 |
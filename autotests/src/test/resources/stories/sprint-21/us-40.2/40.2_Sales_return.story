Meta:
@sprint 21
@us 40.2

Narrative:
As a заведующий отдела, при получении данных о продажах из SR10
I want to чтобы чеки возврата не изменяли актуальные товарные остатки

Scenario: Sales return (xml)

Given skipped. Info: 'Skipped story', Details: 'Not actual'
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
Given the user opens amount list page
Then the user checks the product with '4008577061437' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user navigates to the product with sku '4008577061437'
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '25,50' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '25,50'
Given the user opens amount list page
Then the user checks the product with '4008577061437' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the robot prepares import return data
And the robot waits the import folder become empty
And the user opens amount list page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
Then the user checks the product with '4008577061437' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
Then the user checks the product with '4008577061437' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the robot prepares import return data
And the robot waits the import folder become empty
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
Then the user checks the product with '4008577061437' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
Then the user checks the product with '4008577061437' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
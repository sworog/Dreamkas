Meta:
@sprint_21
@us_40.1

Narrative:
As a заведующий отдела, при получении данных о продажах из SR10,
I want to чтобы повторно выгруженные чеки не изменяли актуальные товарные остатки

Scenario: Sale clone import (xml)

Given skipped. Info: 'Skipped', Details: 'Not actual'

Given the user runs the symfony:env:init command

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-import-url | smb://faro.lighthouse.pro/centrum/reports |
| set10-import-login | erp |
| set10-import-interval | 60 |
| set10-import-password | erp |
And the user clicks import save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user logs out
Given there is the subCategory with name 'ProductsExportSubCategory' related to group named 'ProductsExportGroup' and category named 'ProductsExportCategory'
And the user sets subCategory 'ProductsExportSubCategory' mark up with max '30' and min '0' values
And there is the product with 'Нескафе 3 в 1 классический растворимый' name, '46079770' barcode, 'unit' units, '20,80' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ProductsExportStoreManager', position 'ProductsExportStoreManager', username 'ProductsExportStoreManager', password 'lighthouse', role 'storeManager'
And there is the user with name 'ProductsExportStoreManager2', position 'ProductsExportStoreManager2', username 'ProductsExportStoreManager2', password 'lighthouse', role 'storeManager'
And there is the store with number '666' managed by 'ProductsExportStoreManager'
And there is the store with number '777' managed by 'ProductsExportStoreManager2'
And the user navigates to the product with name 'Нескафе 3 в 1 классический растворимый'
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '26,99' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '26,99'
Given the user opens amount list page
Then the user checks the product with '46079770' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user navigates to the product with name 'Нескафе 3 в 1 классический растворимый'
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '25,50' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '25,50'
Given the user opens amount list page
Then the user checks the product with '46079770' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user prepares import clone data for us 40.1 story
And the user opens amount list page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
Then the user checks the product with '46079770' sku has 'amounts amount' element equal to '-2' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
Then the user checks the product with '46079770' sku has 'amounts amount' element equal to '-3' on amounts page
When the user logs out
Given the user prepares import clone data for us 40.1 story
Given the user logs in as 'commercialManager'
And the user opens the log page
Then the user checks log messages
| logMessage |
| Sales import fail: hash: Такая продажа уже зарегистрированна в системе |
| Sales import fail: hash: Такая продажа уже зарегистрированна в системе |
When the user logs out
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
Then the user checks the product with '46079770' sku has 'amounts amount' element equal to '-2' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
Then the user checks the product with '46079770' sku has 'amounts amount' element equal to '-3' on amounts page
When the user logs out




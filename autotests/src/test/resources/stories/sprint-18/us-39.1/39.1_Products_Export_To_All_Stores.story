Meta:
@sprint 19
@us 39.1

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Products export

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-integration-url | smb://faro.lighthouse.cs/centrum/products |
| set10-integration-login | erp |
| set10-integration-password | erp |
And the user clicks save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user logs out
Given there is the subCategory with name 'ProductsExportSubCategory' related to group named 'ProductsExportGroup' and category named 'ProductsExportCategory'
And the user sets subCategory 'ProductsExportSubCategory' mark up with max '30' and min '0' values
And there is the product with 'Кит Кат' name, 'Кит-Кат-343424' sku, '34342478239479230479023' barcode, 'unit' units, '24' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ProductsExportStoreManager', position 'ProductsExportStoreManager', username 'ProductsExportStoreManager', password 'lighthouse', role 'storeManager'
And there is the user with name 'ProductsExportStoreManager2', position 'ProductsExportStoreManager2', username 'ProductsExportStoreManager2', password 'lighthouse', role 'storeManager'
And there is the store with number '666' managed by 'ProductsExportStoreManager'
And there is the store with number '777' managed by 'ProductsExportStoreManager2'
And the user navigates to the product with sku 'Кит-Кат-343424'
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '24,20' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '24,20'
When the user logs out
Given the user navigates to the product with sku 'Кит-Кат-343424'
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '24,55' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '24,55'
When the user logs out
Given the user cleans the cash db by '172.16.2.166' ip
And the user cleans the cash db by '172.16.1.165' ip
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on products export link
Given the robot waits for complete export
And the user opens the log page
Then the user checks the last set10 export log title is 'Выгрузка товаров в Set Retail 10'
Then the user waits for the last set10 export log message success status
And the user checks the last set10 export log status text is 'выполнено'
When the robot starts the test named 'ru.crystals.setrobot.tests.lighthouse.SellKitKatShop2Test' on cashregistry with '172.16.2.166'
Then the robot waits for the test success status
When the robot starts the test named 'ru.crystals.setrobot.tests.lighthouse.SellKitKatShop1Test' on cashregistry with '172.16.1.165'
Then the robot waits for the test success status

Scenario: No export link for departmentManager

Given the user opens catalog page
And the user logs in as 'departmentManager'
Then the user checks the products export link is not present

Scenario: No export link for storeManager

Given there is the user with name 'ProductsExportStoreManager', position 'ProductsExportStoreManager', username 'ProductsExportStoreManager', password 'lighthouse', role 'storeManager'
And there is the store with number '666' managed by 'ProductsExportStoreManager'
And the user opens catalog page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
Then the user checks the products export link is not present


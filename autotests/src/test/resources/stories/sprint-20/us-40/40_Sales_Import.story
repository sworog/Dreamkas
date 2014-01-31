Meta:
@us 40
@sprint 20

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Import products purchases (xml)

Meta:
@id s20u40s1
@smoke

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
And there is the product with 'Конф.жев.Фруттелла 4 вкуса 42.5г' name, '87108521 ' sku, '87108521 ' barcode, 'unit' units, '20,80' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ProductsExportStoreManager', position 'ProductsExportStoreManager', username 'ProductsExportStoreManager', password 'lighthouse', role 'storeManager'
And there is the user with name 'ProductsExportStoreManager2', position 'ProductsExportStoreManager2', username 'ProductsExportStoreManager2', password 'lighthouse', role 'storeManager'
And there is the store with number '666' managed by 'ProductsExportStoreManager'
And there is the store with number '777' managed by 'ProductsExportStoreManager2'
And the user navigates to the product with sku '87108521'
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '26,99' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '26,99'
Given the user opens amount list page
Then the user checks the product with '87108521' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user navigates to the product with sku '87108521'
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '25,50' in 'retailPrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks the 'retailPrice' value is '25,50'
Given the user opens amount list page
Then the user checks the product with '87108521' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the robot prepares import purchase data
And the robot waits the import folder become empty
And the user opens amount list page
When the user logs in using 'ProductsExportStoreManager' userName and 'lighthouse' password
Then the user checks the product with '87108521' sku has 'amounts amount' element equal to '-12' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'ProductsExportStoreManager2' userName and 'lighthouse' password
Then the user checks the product with '87108521' sku has 'amounts amount' element equal to '-6' on amounts page

Scenario: Import no such product data (xml)

Given there is the subCategory with name 'ProductsExportSubCategory' related to group named 'ProductsExportGroup' and category named 'ProductsExportCategory'
And the user sets subCategory 'ProductsExportSubCategory' mark up with max '30' and min '0' values
And there is the user with name 'ProductsExportStoreManager', position 'ProductsExportStoreManager', username 'ProductsExportStoreManager', password 'lighthouse', role 'storeManager'
And there is the user with name 'ProductsExportStoreManager2', position 'ProductsExportStoreManager2', username 'ProductsExportStoreManager2', password 'lighthouse', role 'storeManager'
And there is the store with number '666' managed by 'ProductsExportStoreManager'
And there is the store with number '777' managed by 'ProductsExportStoreManager2'
Given the user prepares import purchase data with no such product for us 40 story

Given the user opens the log page
And the user logs in as 'commercialManager'
Then the user checks the last simple log message
| logMessage |
| Sales import fail: Product with sku "871085219077834" not found |

Scenario: Import no store exist data (xml)

Given the user prepares import purchase data with no such store for us 40 story

Given the user opens the log page
And the user logs in as 'commercialManager'
Then the user checks the last simple log message
| logMessage |
| Sales import fail: Store with number "3455453453" not found |

Scenario: Import currupted data (xml)

Given the user prepares import purchase data with corrupted data for us 40 story

Given the user opens the log page
And the user logs in as 'commercialManager'
Then the user checks the last simple log message
| logMessage |
| Sales import fail: Failed to parse node 'purchase': Couldn't find end of Start Tag position |


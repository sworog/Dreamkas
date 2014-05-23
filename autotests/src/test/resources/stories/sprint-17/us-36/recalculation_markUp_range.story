Meta:
@sprint_17
@us_36

Narrative:
As a коммерческий директор
I want to чтобы розничная цена товара в магазинах всегда находилась в пределах установленного диапазона
In order to чтобы цены в магазинах соответствовали ценовой политики торговой сети

Scenario: Regress - No store storeManager get 403 after try to open product page url

Given skipped test
!--Тест заскипан. Стал падать после того как дали права сторе менеджеру на просмотр доп штрихкодов у продукта.
Given there is the product with 'storeProductName14' name, 'storeProductBarCode14' barcode, 'weight' type, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest100' rounding
And the user navigates to the product with name 'storeProductName14'
And the user logs in as 'storeManager'
Then the user sees the 403 error

Scenario: Out of the bounds checking

Given there is the subCategory with name 'ReCalcSubCategory' related to group named 'ReCalcGroup' and category named 'ReCalcCategory'
And the user sets subCategory 'ReCalcSubCategory' mark up with max '30' and min '10' values
And there is the product with productName, random generated barcode, 'weight' type, '20' purchasePrice of group named 'ReCalcGroup', category named 'ReCalcCategory', subcategory named 'ReCalcSubCategory' with 'nearest1' rounding
And there is the user with <userName>, password 'lighthouse', role 'storeManager'
And there is the store with <storeNumber> managed by <userName>
And the user navigates to the product with productName
When the user logs in using <userName> and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <value> in <elementName> field
And the user clicks the create button
Then the user checks the <elementName> value is <value>
When the user logs out
Given the user logs in as 'commercialManager'
And the user navigates to the product with productName
When the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs values in element fields
| elementName | value |
| retailPriceMax | 24 |
| retailPriceMin | 23 |
And the user clicks the create button
Given the user opens the log page
Then the user checks the last log title is 'Перерасчет цен продукта'
And the user checks the last log product is <productName>
And the user waits for the last log message success status
And the user checks the last log status text is 'выполнено'
When the user logs out
Given the user navigates to the product with productName
When the user logs in using <userName> and 'lighthouse' password
Then the user checks the <elementName> value is <expectedValue>

Examples:
| productName | userName | storeNumber | value | elementName |expectedValue |
| ReCalcProductName | ReCalcStoreManager | ReCalcStore1 | 22 | retailPrice | 23 |
| ReCalcProductName1 | ReCalcStoreManager1 | ReCalcStore1 | 22,99 | retailPrice | 23 |
| ReCalcProductName2 | ReCalcStoreManager2 | ReCalcStore1 | 23,01 | retailPrice | 23,01 |
| ReCalcProductName3 | ReCalcStoreManager3 | ReCalcStore1 | 23,56 | retailPrice | 23,56 |
| ReCalcProductName4 | ReCalcStoreManager4 | ReCalcStore1 | 26 | retailPrice | 24 |
| ReCalcProductName5 | ReCalcStoreManager5 | ReCalcStore1 | 24,01 | retailPrice | 24 |
| ReCalcProductName6 | ReCalcStoreManager6 | ReCalcStore1 | 23,99 | retailPrice | 23,99 |





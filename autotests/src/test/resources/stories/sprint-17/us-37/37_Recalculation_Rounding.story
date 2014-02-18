Meta:
@sprint_17
@us_37

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Store product nearest99 rounding recalculation with price range is set

Given there is the subCategory with name 'ReCalcRoundingSubCategory' related to group named 'ReCalcRoundingGroup' and category named 'ReCalcRoundingCategory'
And the user sets subCategory 'ReCalcRoundingSubCategory' mark up with max '100' and min '0' values
And there is the product with <productName>, <productSku>, 'ReCalcRoundingProduct' barcode, 'kg' units, '10' purchasePrice of group named 'ReCalcRoundingGroup', category named 'ReCalcRoundingCategory', subcategory named 'ReCalcRoundingSubCategory' with 'nearest1' rounding
And there is the user with <userName>, password 'lighthouse', role 'storeManager'
And there is the store with <storeNumber> managed by <userName>
And the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <value> in <elementName> field
And the user clicks the create button
Then the user checks the <elementName> value is <value>
When the user logs out
Given the user logs in as 'commercialManager'
And the user navigates to the product with <productSku>
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| rounding | до 99 копеек |
And the user clicks the create button
Given the user opens the log page
Then the user checks the last log title is 'Перерасчет цен продукта'
And the user checks the last log product is <productName>
And the user waits for the last log message success status
And the user checks the last log status text is 'выполнено'
When the user logs out
Given the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
Then the user checks the <elementName> value is <expectedValue>

Examples:
| productName | productSku | userName | storeNumber | value | elementName |expectedValue |
| ReCalcRoundingProductName | ReCalcRoundingProduct | ReCalcStoreManager | ReCalcStore1 | 10,00 | retailPrice | 9,99 |
| ReCalcRoundingProductName1 | ReCalcRoundingProduct1 | ReCalcStoreManager1 | ReCalcStore1 | 20 | retailPrice | 19,99 |
| ReCalcRoundingProductName2 | ReCalcRoundingProduct2| ReCalcStoreManager2 | ReCalcStore1 | 19,50 | retailPrice | 19,99 |
| ReCalcRoundingProductName3 | ReCalcRoundingProduct3 | ReCalcStoreManager3 | ReCalcStore1 | 19,49 | retailPrice | 18,99 |

Scenario: Store product nearest50 rounding recalculation with price range is set

Given there is the subCategory with name 'ReCalcRoundingSubCategory' related to group named 'ReCalcRoundingGroup' and category named 'ReCalcRoundingCategory'
And the user sets subCategory 'ReCalcRoundingSubCategory' mark up with max '100' and min '0' values
And there is the product with <productName>, <productSku>, 'ReCalcRoundingProduct' barcode, 'kg' units, '1' purchasePrice of group named 'ReCalcRoundingGroup', category named 'ReCalcRoundingCategory', subcategory named 'ReCalcRoundingSubCategory' with 'nearest1' rounding
And there is the user with <userName>, password 'lighthouse', role 'storeManager'
And there is the store with <storeNumber> managed by <userName>
And the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <value> in <elementName> field
And the user clicks the create button
Then the user checks the <elementName> value is <value>
When the user logs out
Given the user logs in as 'commercialManager'
And the user navigates to the product with <productSku>
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| rounding | до 50 копеек |
And the user clicks the create button
Given the user opens the log page
Then the user checks the last log title is 'Перерасчет цен продукта'
And the user checks the last log product is <productName>
And the user waits for the last log message success status
And the user checks the last log status text is 'выполнено'
When the user logs out
Given the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
Then the user checks the <elementName> value is <expectedValue>

Examples:
| productName | productSku | userName | storeNumber | value | elementName |expectedValue |
| ReCalcRoundingProductName4 | ReCalcRoundingProduct4 | ReCalcStoreManager | ReCalcStore1 | 1,24 | retailPrice | 1,00 |
| ReCalcRoundingProductName5 | ReCalcRoundingProduct5 | ReCalcStoreManager1 | ReCalcStore1 | 1,25 | retailPrice | 1,50 |
| ReCalcRoundingProductName6 | ReCalcRoundingProduct6| ReCalcStoreManager2 | ReCalcStore1 | 1,74 | retailPrice | 1,50 |
| ReCalcRoundingProductName7 | ReCalcRoundingProduct7 | ReCalcStoreManager3 | ReCalcStore1 | 1,75 | retailPrice | 2,00 |

Scenario: Store product nearest100 rounding recalculation with price range is set

Given there is the subCategory with name 'ReCalcRoundingSubCategory' related to group named 'ReCalcRoundingGroup' and category named 'ReCalcRoundingCategory'
And the user sets subCategory 'ReCalcRoundingSubCategory' mark up with max '100' and min '0' values
And there is the product with <productName>, <productSku>, 'ReCalcRoundingProduct' barcode, 'kg' units, '10' purchasePrice of group named 'ReCalcRoundingGroup', category named 'ReCalcRoundingCategory', subcategory named 'ReCalcRoundingSubCategory' with 'nearest1' rounding
And there is the user with <userName>, password 'lighthouse', role 'storeManager'
And there is the store with <storeNumber> managed by <userName>
And the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <value> in <elementName> field
And the user clicks the create button
Then the user checks the <elementName> value is <value>
When the user logs out
Given the user logs in as 'commercialManager'
And the user navigates to the product with <productSku>
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| rounding | до рублей |
And the user clicks the create button
Given the user opens the log page
Then the user checks the last log title is 'Перерасчет цен продукта'
And the user checks the last log product is <productName>
And the user waits for the last log message success status
And the user checks the last log status text is 'выполнено'
When the user logs out
Given the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
Then the user checks the <elementName> value is <expectedValue>

Examples:
| productName | productSku | userName | storeNumber | value | elementName |expectedValue |
| ReCalcRoundingProductName8 | ReCalcRoundingProduct8 | ReCalcStoreManager | ReCalcStore1 | 10,49 | retailPrice | 10,00 |
| ReCalcRoundingProductName9 | ReCalcRoundingProduct9 | ReCalcStoreManager1 | ReCalcStore1 | 10,50 | retailPrice | 11,00 |
| ReCalcRoundingProductName10 | ReCalcRoundingProduct10 | ReCalcStoreManager2 | ReCalcStore1 | 10,99 | retailPrice | 11,00 |

Scenario: Store product nearest10 rounding recalculation with price range is set

Given there is the subCategory with name 'ReCalcRoundingSubCategory' related to group named 'ReCalcRoundingGroup' and category named 'ReCalcRoundingCategory'
And the user sets subCategory 'ReCalcRoundingSubCategory' mark up with max '100' and min '0' values
And there is the product with <productName>, <productSku>, 'ReCalcRoundingProduct' barcode, 'kg' units, '10' purchasePrice of group named 'ReCalcRoundingGroup', category named 'ReCalcRoundingCategory', subcategory named 'ReCalcRoundingSubCategory' with 'nearest1' rounding
And there is the user with <userName>, password 'lighthouse', role 'storeManager'
And there is the store with <storeNumber> managed by <userName>
And the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <value> in <elementName> field
And the user clicks the create button
Then the user checks the <elementName> value is <value>
When the user logs out
Given the user logs in as 'commercialManager'
And the user navigates to the product with <productSku>
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| rounding | до 10 копеек |
And the user clicks the create button
Given the user opens the log page
Then the user checks the last log title is 'Перерасчет цен продукта'
And the user checks the last log product is <productName>
And the user waits for the last log message success status
And the user checks the last log status text is 'выполнено'
When the user logs out
Given the user navigates to the product with <productSku>
When the user logs in using <userName> and 'lighthouse' password
Then the user checks the <elementName> value is <expectedValue>

Examples:
| productName | productSku | userName | storeNumber | value | elementName |expectedValue |
| ReCalcRoundingProductName11 | ReCalcRoundingProduct11 | ReCalcStoreManager | ReCalcStore1 | 10,49 | retailPrice | 10,50 |
| ReCalcRoundingProductName12 | ReCalcRoundingProduct12 | ReCalcStoreManager1 | ReCalcStore1 | 10,54 | retailPrice | 10,50 |
| ReCalcRoundingProductName13 | ReCalcRoundingProduct13 | ReCalcStoreManager2 | ReCalcStore1 | 10,55 | retailPrice | 10,60 |

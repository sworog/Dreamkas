Meta:
@sprint_17
@us_34

Narrative:
As a директор магазина
I want to установить розничную цену на товар в рамках указанного диапазона наценки,
In order to определить цену на товар для своего магазина

Scenario: Product store mark up set positive

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName' name, 'storeProductSku' sku, 'storeProductBarCode' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the store 'StoreProduct123' catalog page
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks on the group name 'storeProductsGroup'
And the user clicks on the category name 'storeProductsCategory'
And the user clicks on the subCategory name 'storeProductsSubCategory'
And the user open the product card with 'storeProductSku' sku
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages
And the user checks the <elementName> value is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 0 | retailMarkup | отсутствует |
| 50 | retailMarkup | 50,00 |
| 35 | retailMarkup | 35,00 |

Scenario: Product stote retail price set positive

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName' name, 'storeProductSku' sku, 'storeProductBarCode' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the store 'StoreProduct123' catalog page
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks on the group name 'storeProductsGroup'
And the user clicks on the category name 'storeProductsCategory'
And the user clicks on the subCategory name 'storeProductsSubCategory'
And the user open the product card with 'storeProductSku' sku
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages
And the user checks the <elementName> value is <expectedValue>

Examples:
| inputText | elementName |  expectedValue |
| 10 | retailPrice | 10,00 |
| 15 | retailPrice | 15,00 |
| 12,45 | retailPrice | 12,45 |

Scenario: Check mark up range

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '49' and min '12' values
And there is the product with 'storeProductName3' name, 'storeProductSku3' sku, 'storeProductBarCode3' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku3'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
Then the user checks the 'retailMarkupRange' value is '12,00 - 49,00'

Scenario: Check retail price range

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName2' name, 'storeProductSku2' sku, 'storeProductBarCode2' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku2'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
Then the user checks the 'retailPriceRange' value is '10,00 - 15,00'


Scenario: Mark up store product price negative

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName1' name, 'storeProductSku1' sku, 'storeProductBarCode1' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku1'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
|-1 | retailMarkup | Наценка должна быть больше или равна 0% |
|-0.01 | retailMarkup | Наценка должна быть больше или равна 0% |
| 12,678 | retailMarkup | Наценка не должна содержать больше 2 цифр после запятой |
| -12,678 | retailMarkup | Наценка не должна содержать больше 2 цифр после запятой. Наценка должна быть больше или равна 0% |
| big price | retailMarkup | Наценка должна быть числом |
| BIG PRICE | retailMarkup | Наценка должна быть числом |
| большая цена | retailMarkup | Наценка должна быть числом |
| БОЛЬШАЯ ЦЕНА | retailMarkup | Наценка должна быть числом |
| !@#$%^&*() | retailMarkup | Наценка должна быть числом |
| 60 | retailMarkup | Наценка должна быть меньше или равна 50% |

Scenario: Retail store product price negative

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName2' name, 'storeProductSku2' sku, 'storeProductBarCode2' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku2'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
| '%^#$Fgbdf345) | retailPrice | Значение должно быть числом |
| 739,678 | retailPrice | Цена не должна содержать больше 2 цифр после запятой. Цена должна быть меньше или равна 15.00 |
| -1 | retailPrice | Цена не должна быть меньше или равна нулю. Цена должна быть больше или равна 10.00. Цена после округления должна быть больше 0 |
| 0 | retailPrice | Цена не должна быть меньше или равна нулю. Цена должна быть больше или равна 10.00. Цена после округления должна быть больше 0 |
| big price | retailPrice | Значение должно быть числом |
| BIG PRICE | retailPrice | Значение должно быть числом |
| большая цена | retailPrice | Значение должно быть числом |
| БОЛЬШАЯ | retailPrice | Значение должно быть числом |
| 1 102,76 | retailPrice | Значение должно быть числом |
| 10000001 | retailPrice | Цена не должна быть больше 10000000. Цена должна быть меньше или равна 15.00 |
| 9 | retailPrice | Цена должна быть больше или равна 10.00 |
| 9.99 | retailPrice | Цена должна быть больше или равна 10.00 |
| 15.01 | retailPrice | Цена должна быть меньше или равна 15.00 |
| 16.00 | retailPrice | Цена должна быть меньше или равна 15.00 |


Scenario: Store manager cant view catalog if he dont manage any store through link

Given there is the user with name 'testName1', position 'testName1', username 'testName1', password 'lighthouse', role 'storeManager'
And the user opens catalog page
When the user logs in using 'testName1' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: Store manager cant view catalog if he dont manage any store through dashboard link click

Given there is the user with name 'testName1', position 'testName1', username 'testName1', password 'lighthouse', role 'storeManager'
And the user opens the authorization page
When the user logs in using 'testName1' userName and 'lighthouse' password
Then the user checks the dashboard link to 'catalog' section is not present

Scenario: If mark up dont set - check mark up under the zero

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName6' name, 'storeProductSku6' sku, 'storeProductBarCode6' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku6'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs '-1' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Наценка должна быть больше или равна 0% |

Scenario: If mark up dont set - check retail price under the purchase price

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName7' name, 'storeProductSku7' sku, 'storeProductBarCode7' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku7'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs '1' in 'retailPrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
|  Цена должна быть больше или равна 10.00 |

Scenario: Check default values are set

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName5' name, 'storeProductSku5' sku, 'storeProductBarCode5' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku5'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
Then the user checks the 'retailMarkupRange' value is '0,00 - 50,00'
And the user checks the 'retailPriceRange' value is '10,00 - 15,00'
And the user checks the 'retailMarkup' value is '50,00'
And the user checks the 'retailPrice' value is '15,00'




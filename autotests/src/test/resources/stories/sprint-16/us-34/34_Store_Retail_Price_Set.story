Meta:
@sprint 16
@us 34

Narrative:
As a директор магазина
I want to установить розничную цену на товар в рамках указанного диапазона наценки,
In order to определить цену на товар для своего магазина

Scenario: Product store mark up set positive

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName' name, 'storeProductSku' sku, 'storeProductBarCode' barcode, 'kg' units, '10' purchasePrice in the subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user opens catalog page
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks on the group name 'storeProductsCategory'
And the user clicks on the category name 'storeProductsGroup'
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

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName' name, 'storeProductSku' sku, 'storeProductBarCode' barcode, 'kg' units, '10' purchasePrice in the subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user opens catalog page
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks on the group name 'storeProductsCategory'
And the user clicks on the category name 'storeProductsGroup'
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

Scenario: check mark up range

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '49' and min '12' values
And there is the product with 'storeProductName3' name, 'storeProductSku3' sku, 'storeProductBarCode3' barcode, 'kg' units, '10' purchasePrice in the subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku3'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
Then the user checks the 'retailMarkupRange' value is '12,00 - 49,00'

Scenario: check retail price range

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName2' name, 'storeProductSku2' sku, 'storeProductBarCode2' barcode, 'kg' units, '10' purchasePrice in the subcategory named 'storeProductsSubCategory'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku2'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
Then the user checks the 'retailPriceRange' value is '10,00 - 15,00'


Scenario: Mark up store product price negative

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName1' name, 'storeProductSku1' sku, 'storeProductBarCode1' barcode, 'kg' units, '10' purchasePrice in the subcategory named 'storeProductsSubCategory'
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
|-1 | retailMarkup | Наценка должна быть равна или больше 0% |
|-0.01 | retailMarkup | Наценка должна быть равна или больше 0% |
| 12,678 | retailMarkup | Значение не должно содержать больше 2 цифр после запятой |
| -12,678 | retailMarkup | Значение не должно содержать больше 2 цифр после запятой. Наценка должна быть равна или больше 0% |
| big price | retailMarkup | Значение должно быть числом |
| BIG PRICE | retailMarkup | Значение должно быть числом |
| большая цена | retailMarkup | Значение должно быть числом |
| БОЛЬШАЯ ЦЕНА | retailMarkup | Значение должно быть числом |
| !@#$%^&*() | retailMarkup | Значение должно быть числом |
| 60 | retailMarkup | Минимальная наценка не должна быть больше максимальной |

Scenario: Retail store product price negative

Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '50' and min '0' values
And there is the product with 'storeProductName2' name, 'storeProductSku2' sku, 'storeProductBarCode2' barcode, 'kg' units, '10' purchasePrice in the subcategory named 'storeProductsSubCategory'
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
| '%^#$Fgbdf345) | retailPrice | Цена не должна быть меньше или равна нулю. |
| 739,678 | retailPrice | Цена не должна содержать больше 2 цифр после запятой. |
| -1 | retailPrice | Цена не должна быть меньше или равна нулю. |
| 0 | retailPrice | Цена не должна быть меньше или равна нулю. |
| big price | retailPrice | Цена не должна быть меньше или равна нулю. |
| BIG PRICE | retailPrice | Цена не должна быть меньше или равна нулю. |
| большая цена | retailPrice | Цена не должна быть меньше или равна нулю. |
| БОЛЬШАЯ | retailPrice | Цена не должна быть меньше или равна нулю. |
| 10000001 | retailPrice | Цена не должна быть больше 10000000 |


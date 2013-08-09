Meta:
@sprint 16
@us 34

Narrative:
As a директор магазина
I want to установить розничную цену на товар в рамках указанного диапазона наценки,
In order to определить цену на товар для своего магазина

Scenario: Product stote mark up set positive

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

Examples:
| inputText | elementName |
| 0 | retailMarkup |
| 50 | retailMarkup |
| 35 | retailMarkup |

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
And the user clicks 'retailPriceHint' element
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages

Examples:
| inputText | elementName |
| 10 | retailPrice |
| 15 | retailPrice |
| 12,45 | retailPrice |

Scenario: check mark up range

Scenario: check retail price range

Scenario: mark up is not set on product

Scenario: Mark up store product price negative

Scenario: Retail store product price negative
Given there is the subCategory with name 'storeProductsSubCategory' related to group named 'storeProductsCategory' and category named 'storeProductsGroup'
And the user sets subCategory 'storeProductsSubCategory' mark up with max '5' and min '1' values
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
And the user inputs <value> in retailPrice
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| value | errorMessage |
| ? | ? |


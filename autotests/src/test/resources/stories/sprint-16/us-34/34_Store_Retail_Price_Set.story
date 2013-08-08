Meta:
@sprint 16
@us 34

Narrative:
As a директор магазина
I want to установить розничную цену на товар в рамках указанного диапазона наценки,
In order to определить цену на товар для своего магазина

Scenario: Retail store product price set positive

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
And the user clicks the edit button on product card view page
And the user inputs '12' in 'retailStoreProductPrice' field
And the user clicks the create button
Then the user sees no error messages

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
And the user clicks the edit button on product card view page
And the user inputs <value> in retailPrice
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| value | errorMessage |
| ? | ? |

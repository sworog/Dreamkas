Meta:
@sprint 17
@us 31

Narrative:
As a коммерческий директор
I want to установить правило округления розничных цен для товаров
In order to управлять политикой цен торговой сети

Scenario: Check rounding on catalog card page

Given there is the product with <productSku> and <rounding> in the subcategory named 'RoundingSubCategory'
And the user navigates to the product with <productSku>
And the user logs in as 'commercialManager'
Then the user checks the rounding value is <expectedValue>

Examples:
| productSku | rounding | expectedValue |
| nearest1 | nearest1 | до копеек |
| nearest10 | nearest10 | до 10 копеек |
| nearest50 | nearest50 | до 50 копеек |
| nearest99 | nearest99 | до 99 копеек |
| nearest100 | nearest100 | до рублей |

Scenario: Check rounding is saved values in edit mode

Given there is the product with <productSku> and <rounding> in the subcategory named 'RoundingSubCategory'
And the user navigates to the product with <productSku>
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
Then the user checks the product rounding value is <value>

Examples:
| productSku | rounding | value |
| nearest1 | nearest1 | до копеек |
| nearest10 | nearest10 | до 10 копеек |
| nearest50 | nearest50 | до 50 копеек |
| nearest99 | nearest99 | до 99 копеек |
| nearest100 | nearest100 | до рублей |

Scenario: Check rounding on store card page

Given there is the product with <productSku> and <rounding> in the subcategory named 'RoundingSubCategory'
And there is the user with name 'storeManagerRounding', position 'storeManagerRounding', username 'storeManagerRounding', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProductsRounding' managed by 'storeManagerRounding'
And the user navigates to the product with <productSku>
When the user logs in using 'storeManagerRounding' userName and 'lighthouse' password
Then the user checks the rounding value is <expectedValue>

Examples:
| productSku | rounding | expectedValue |
| nearest1 | nearest1 | до копеек |
| nearest10 | nearest10 | до 10 копеек |
| nearest50 | nearest50 | до 50 копеек |
| nearest99 | nearest99 | до 99 копеек |
| nearest100 | nearest100 | до рублей |

Scenario: Rounding inheritance from subCategory

Given there is the subCategory with rounding set to 'nearest100' with name 'RoundingSubCategorySet' related to group named 'RoundingGroup' and category named 'RoundingCategory'
And the user navigates to the subCategory 'RoundingSubCategorySet', category 'RoundingCategory', group 'RoundingGroup' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
When the user creates new product from product list page
Then the user checks the product price roundings dropdawn default selected value is 'до рублей'

Scenario: Rounding default values if rounding is default on subCategory

Given there is the subCategory with name 'RoundingSubCategory' related to group named 'RoundingGroup' and category named 'RoundingCategory'
And the user navigates to the subCategory 'RoundingSubCategory', category 'RoundingCategory', group 'RoundingGroup' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
When the user creates new product from product list page
Then the user checks the product price roundings dropdawn default selected value is 'до копеек'

Scenario: Regress - if mark up is not set store product price should be equal purchase price

Given there is the subCategory with name 'storeProductsSubCategoryRegress' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And there is the product with 'storeProductName' name, 'storeProductSku10' sku, 'storeProductBarCode' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryRegress'
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku10'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
Then the user checks the 'retailMarkupRange' value is 'отсутствует'
And the user checks the 'retailPriceRange' value is 'отсутствует'
And the user checks the 'retailMarkup' value is 'отсутствует'
And the user checks the 'retailPrice' value is 'отсутствует'
And the user checks the edit price button is not present
And the user checks page contains text 'Изменить наценку/цену нельзя. Нет диапазона наценки'

Scenario: Mark up rounding price check nearest1

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName99' name, 'storeProductSku99' sku, 'storeProductBarCode99' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest1' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku99'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 0 | retailMarkup | 10,00 |
| 5 | retailMarkup | 10,50 |
| 43,45 | retailMarkup | 14,35 |
| 99,91 | retailMarkup | 19,99 |

Scenario: Mark up rounding price check nearest10

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName991' name, 'storeProductSku991' sku, 'storeProductBarCode991' barcode, 'kg' units, '1' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest10' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku991'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 4 | retailMarkup | 1,00 |
| 5 | retailMarkup | 1,10 |
| 45,78 | retailMarkup | 1,50 |

Scenario: Mark up rounding price check nearest100

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName992' name, 'storeProductSku992' sku, 'storeProductBarCode992' barcode, 'kg' units, '1' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest100' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku992'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 45 | retailMarkup | 1,00 |
| 49,99 | retailMarkup | 2,00 |
| 99 | retailMarkup | 2,00 |


Scenario: Mark up rounding price check nearest50

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName993' name, 'storeProductSku993' sku, 'storeProductBarCode993' barcode, 'kg' units, '1' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest50' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku993'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 49 | retailMarkup | 1,50 |
| 74 | retailMarkup | 1,50 |
| 99 | retailMarkup | 2,00 |

Scenario: Mark up rounding price check nearest99

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName994' name, 'storeProductSku994' sku, 'storeProductBarCode994' barcode, 'kg' units, '1' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest99' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku994'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user inputs <inputText> in <elementName> field
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 100 | retailMarkup | 1,99 |
| 49 | retailMarkup | 0,99 |
| 50 | retailMarkup | 1,99 |

Scenario: Retail price rounding price check nearest99

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName9911' name, 'storeProductSku9911' sku, 'storeProductBarCode9911' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest99' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku9911'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks 'rounding price' element
Then the user checks the 'rounding price' is <expectedValue>
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 10,00 | retailPrice | 9,99 |
| 20 | retailPrice | 19,99 |
| 19,50 | retailPrice | 19,99 |
| 19,49 | retailPrice | 18,99 |

Scenario: Retail price rounding price check nearest99 negative

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName9912' name, 'storeProductSku9912' sku, 'storeProductBarCode9912' barcode, 'kg' units, '0,40' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest99' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku9912'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks 'rounding price' element
Then the user checks the 'rounding price' is <expectedValue>
When the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | expectedValue | errorMessage |
| 0,49 | retailPrice | 0,00 | Цена после округления должна быть больше 0 |
| 0,4 | retailPrice | 0,00 | Цена после округления должна быть больше 0 |

Scenario: Retail price rounding price check nearest50

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName9913' name, 'storeProductSku9913' sku, 'storeProductBarCode9913' barcode, 'kg' units, '1' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest50' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku9913'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks 'rounding price' element
Then the user checks the 'rounding price' is <expectedValue>
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 1,24 | retailPrice | 1,00 |
| 1,25 | retailPrice | 1,50 |
| 1,74 | retailPrice | 1,50 |
| 1,75 | retailPrice | 2,00 |

Scenario: Retail price rounding price check nearest100

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName9914' name, 'storeProductSku9914' sku, 'storeProductBarCode9914' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest100' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku9914'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks 'rounding price' element
Then the user checks the 'rounding price' is <expectedValue>
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 10,49 | retailPrice | 10,00 |
| 10,50 | retailPrice | 11,00 |
| 10,99 | retailPrice | 11,00 |

Scenario: Retail price rounding price check nearest10

Given there is the subCategory with name 'storeProductsSubCategoryOne' related to group named 'storeProductsGroup' and category named 'storeProductsCategory'
And the user sets subCategory 'storeProductsSubCategoryOne' mark up with max '100' and min '0' values
And there is the product with 'storeProductName9915' name, 'storeProductSku9915' sku, 'storeProductBarCode9915' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest10' rounding
And there is the user with name 'storeManagerProducts', position 'storeManagerProducts', username 'storeManagerProducts', password 'lighthouse', role 'storeManager'
And there is the store with number 'StoreProduct123' managed by 'storeManagerProducts'
And the user navigates to the product with sku 'storeProductSku9915'
When the user logs in using 'storeManagerProducts' userName and 'lighthouse' password
And the user clicks the edit price button
And the user clicks retailPriceHint to make retailPrice available
And the user inputs <inputText> in <elementName> field
And the user clicks 'rounding price' element
Then the user checks the 'rounding price' is <expectedValue>
When the user clicks the create button
Then the user checks the 'retailPrice' is <expectedValue>

Examples:
| inputText | elementName | expectedValue |
| 10,49 | retailPrice | 10,50 |
| 10,54 | retailPrice | 10,50 |
| 10,55 | retailPrice | 10,60 |

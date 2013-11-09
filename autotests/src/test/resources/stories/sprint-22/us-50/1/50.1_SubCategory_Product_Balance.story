Meta:
@sprint 22
@us 50.1

Narrative:
As a заведующтй отделом
I want to просматривать остатки товаров подкатегории
In order to понять когда и сколько едениц товара мне требуется заказать у поставщика

Scenario: Subcategory product balance checking

Meta:
@id s22u50.1t1
@description subcategory page have balance tab, balance table have all required data

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name' name, 'SCPBC-sku' sku, 'SCPBC-barcode' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku | SCPBC-name | SCPBC-barcode | 0 | шт. | — | 12,34 р. |

Scenario: Subcategory product balance not required fields checking

Meta:
@id s22u50.1t2
@description balance data table render not required produt fields correctly

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-1' name, 'SCPBC-sku-1' sku, '' barcode, 'unit' units, '' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku-1 | SCPBC-name-1 | | 0 | шт. | — | — |

Scenario: Subcategory product balance after writeOff

Meta:
@id s22u50.1t3
@description balance deacrese after writeOff with product is created

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-2' name, 'SCPBC-sku-2' sku, 'SCPBC-barcode-2' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the invoice in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| sku | SCPBC-01 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'SCPBC-01' with sku 'SCPBC-sku-2', quantity '1', price '100' in the store ruled by 'departmentManager-SCPBC'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku-2 | SCPBC-name-2 | SCPBC-barcode-2 | 1 | шт. | — | 100,00 р. |

Scenario: Subcategory product balance after invoice

Meta:
@id s22u50.1t4
@description balance increase after invoice with product is created

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-3' name, 'SCPBC-sku-3' sku, 'SCPBC-barcode-3' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-01 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-01' with sku 'SCPBC-sku-3', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku-3 | SCPBC-name-3 | SCPBC-barcode-3 | -1 | шт. | — | 12,34 р. |


Scenario: Subcategory product balance with average price checking

Meta:
@id s22u50.1t5
@description balance table average price column contains product correct data

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-4' name, 'SCPBC-sku-4' sku, 'SCPBC-barcode-4' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user clicks the create button on the invoice list page
And the user inputs 'SCPBC-02' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'SCPBC-name-4' in the invoice product 'productName' field
When the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '145' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks the create button on the invoice list page
And the user inputs 'SCPBC-03' in the invoice 'sku' field
And the user inputs 'todayDateAndTime-15day' in the invoice 'acceptanceDate' field
And the user inputs 'поставщик' in the invoice 'supplier' field
And the user inputs 'кто принял' in the invoice 'accepter' field
And the user inputs 'получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user inputs 'SCPBC-name-4' in the invoice product 'productName' field
When the user inputs '2' in the invoice product 'productAmount' field
And the user inputs '123' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Given starting average price calculation
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku-4 | SCPBC-name-4 | SCPBC-barcode-4 | 3 | шт. | 130,33 р. | 123,00 р. |

Scenario: No product balance tab for storeManager

Meta:
@id s22u50.1t6
@description no product balance tab availabile for store manager

Given there is the user with name 'NPBTFST-1', position 'NPBTFST-1', username 'NPBTFST-1', password 'lighthouse', role 'storeManager'
And there is the store with number 'NPBTFST' managed by 'NPBTFST-1'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'NPBTF-name-1' name, 'SCPBC-sku-1' sku, 'SCPBC-barcode-1' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'NPBTFST-1' userName and 'lighthouse' password
Then the user checks product balance tab is not visible

Scenario: No product balance tab for commercialManager

Meta:
@id s22u50.1t7
@description no product balance tab availabile for commercial manager

Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'NPBTF-name-1' name, 'SCPBC-sku-1' sku, 'SCPBC-barcode-1' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
Given the user logs in as 'commercialManager'
Then the user checks product balance tab is not visible














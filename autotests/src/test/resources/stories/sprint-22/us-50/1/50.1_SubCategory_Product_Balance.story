Meta:
@sprint 22
@us 50.1

Narrative:
As a заведующтй отделом
I want to просматривать остатки товаров подкатегории
In order to понять когда и сколько едениц товара мне требуется заказать у поставщика

Scenario: Subcategory product balance checking

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

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-1' name, 'SCPBC-sku-1' sku, '' barcode, 'unit' units, '' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku-1 | SCPBC-name-1 | — | 0 | шт. | — | — |

Scenario: Subcategory product balance after writeOff

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
| SCPBC-sku | SCPBC-name | SCPBC-barcode | 1 | шт. | — | 100,00 р. |

Scenario: Subcategory product balance after invoice

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-3' name, 'SCPBC-sku-3' sku, 'SCPBC-barcode-3' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-01 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-01' with sku '7300330094026', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku | SCPBC-name | SCPBC-barcode | -1 | шт. | — | 12,34 р. |


Scenario: Subcategory product balance with average price checking

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-4' name, 'SCPBC-sku-4' sku, 'SCPBC-barcode-4' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the invoice in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| sku | SCPBC-02 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'SCPBC-02' with sku 'SCPBC-sku-4', quantity '1', price '145' in the store ruled by 'departmentManager-SCPBC'
And there is the invoice in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| sku | SCPBC-03 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'SCPBC-03' with sku 'SCPBC-sku-4', quantity '2', price '123' in the store ruled by 'departmentManager-SCPBC'
And starting average price calculation
And the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| sku | name | barcode | balance | units | averagePurchasePrice | lastPurchasePrice |
| SCPBC-sku | SCPBC-name | SCPBC-barcode | 1 | шт. | 154,67 р. | 100,00 р. |














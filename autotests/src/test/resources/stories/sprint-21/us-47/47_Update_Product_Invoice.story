Meta:
@sprint 21
@us 47

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Nothing found

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateInvoiceSubCategory' related to group named 'ProductsUpdateInvoiceGroup' and category named 'ProductsUpdateInvoiceCategory'
And there is the product with 'Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г' name, '7300330094025' sku, '7300330094025' barcode, 'unit' units, '97,60' purchasePrice of group named 'ProductsUpdateInvoiceGroup', category named 'ProductsUpdateInvoiceCategory', subcategory named 'ProductsUpdateInvoiceSubCategory'
And the user navigates to the product with sku '7300330094025'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation invoices link
Then the user checks page contains text 'Приходов товара еще не было'

Scenario: Found one invoice

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateInvoiceSubCategory' related to group named 'ProductsUpdateInvoiceGroup' and category named 'ProductsUpdateInvoiceCategory'
And there is the product with 'Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г' name, '7300330094025' sku, '7300330094025' barcode, 'unit' units, '97,60' purchasePrice of group named 'ProductsUpdateInvoiceGroup', category named 'ProductsUpdateInvoiceCategory', subcategory named 'ProductsUpdateInvoiceSubCategory'
And there is the invoice in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| sku | UIBS-FF-01 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'UIBS-FF-01' with sku '7300330094025', quantity '1', price '100' in the store ruled by 'departmentManager-UIBS-FF'
And the user navigates to the product with sku '7300330094025'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation invoices link
Then the user checks the product invoices list contains entry
| acceptanceDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 1,000 | 100,00 | 100,00 |

Scenario: Found more invoices

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateInvoiceSubCategory' related to group named 'ProductsUpdateInvoiceGroup' and category named 'ProductsUpdateInvoiceCategory'
And there is the product with 'Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г' name, '7300330094025' sku, '7300330094025' barcode, 'unit' units, '97,60' purchasePrice of group named 'ProductsUpdateInvoiceGroup', category named 'ProductsUpdateInvoiceCategory', subcategory named 'ProductsUpdateInvoiceSubCategory'
And there is the invoice in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| sku | UIBS-FF-02 |
| acceptanceDate | 01.04.2013 12:22 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 01.04.2013 |
And the user adds the product to the invoice with name 'UIBS-FF-02' with sku '7300330094025', quantity '2', price '99' in the store ruled by 'departmentManager-UIBS-FF'
And there is the invoice in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| sku | UIBS-FF-03 |
| acceptanceDate | 04.04.2013 16:25 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 04.04.2013 |
And the user adds the product to the invoice with name 'UIBS-FF-03' with sku '7300330094025', quantity '33', price '77' in the store ruled by 'departmentManager-UIBS-FF'
And the user navigates to the product with sku '7300330094025'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation invoices link
Then the user checks the product invoices list contains entry
| acceptanceDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 1,000 | 100,00 | 100,00 |
| 01.04.2013 | 2,000 | 99,00 | 198,00 |
| 04.04.2013 | 33,000 | 77,00 | 2 541,00 |
When the user clicks invoice sku 'UIBS-FF-03'
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | UIBS-FF-03 |
| acceptanceDate | 04.04.2013 16:25 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 04.04.2013 |
Then the user checks the product with '7300330094025' sku has values
| elementName | value |
| productName | Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г |
| productSku | 7300330094025 |
| productBarcode | 7300330094025 |
| productUnits | шт |
| productAmount | 33 |
| productPrice | 77 |
| productSum | 2541 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 2541 |
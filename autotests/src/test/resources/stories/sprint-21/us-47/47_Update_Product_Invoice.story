Meta:
@sprint 21
@us 47

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Test

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateInvoiceSubCategory' related to group named 'ProductsUpdateInvoiceGroup' and category named 'ProductsUpdateInvoiceCategory'
And there is the product with 'Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г' name, '7300330094025 ' sku, '7300330094025 ' barcode, 'unit' units, '97,60' purchasePrice of group named 'ProductsUpdateInvoiceGroup', category named 'ProductsUpdateInvoiceCategory', subcategory named 'ProductsUpdateInvoiceSubCategory'
And there is the invoice in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| sku | 'UIBS-FF-01 |
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
| date | number| price |
| 02.04.2013 16:23 | 1 | 100 |








!--only for departmentManager
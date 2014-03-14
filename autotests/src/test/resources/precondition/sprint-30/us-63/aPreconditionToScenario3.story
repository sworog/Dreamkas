Meta:
@smoke
@sprint_30
@us_63

Scenario: A scenario that prepares data

Given there is the product with 'name-3063-1' name, '3063-1' sku, '3063-1' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'

Given there is the invoice in the store with number 'store-s30u63' ruled by department manager with name 'departmentManager-s30u63' with values
| elementName | elementValue |
| sku | 3063-01 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name '3063-01' with sku '3063-1', quantity '12,456', price '110' in the store ruled by 'departmentManager-s30u63'



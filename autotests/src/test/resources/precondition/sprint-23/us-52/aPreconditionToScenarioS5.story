Meta:
@sprint_23
@us_52
@id_s23u52s5

Scenario: A scenario that prepares data

Given there is the product with 'name-2352-2' name, 'sku-2352-2' sku, 'barcode-2352-2' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
Given there is the invoice in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| sku | invoice-2352-2 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'invoice-2352-2' with sku 'sku-2352-2', quantity '3', price '126,99' in the store ruled by 'departmentManager-s23u52'
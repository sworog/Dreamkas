Meta:
@sprint_23
@us_52
@id_s23u52s1
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'name-2352' name, 'barcode-2352' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | sku-2352 |
| quantity | 3,675 |
| price | 126,99 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u52'
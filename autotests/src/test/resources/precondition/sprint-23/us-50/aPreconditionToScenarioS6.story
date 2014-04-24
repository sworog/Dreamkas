Meta:
@sprint_23
@us_50
@id_s23u50s6

Scenario: A scenario that prepares data

Given there is the product with 'name-s23u50-2' name, '280046544' sku, '280046544' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-15days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 280046544 |
| quantity | 100 |
| price | 120,00 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u50'

Given the user runs the prepare fixture data cap command for negative inventory testing - '0' days
And the user runs the recalculate_metrics cap command
Meta:
@sprint_23
@us_50
@id_s23u50s7

Scenario: A scenario that prepares data

Given there is the product with 'name-s23u50-3' name, '2800465441' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-15days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2800465441 |
| quantity | 100 |
| price | 120,00 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u50'

Given the user runs the prepare fixture data cap command for negative inventory testing 2 - '31' days
And the user runs the recalculate_metrics cap command
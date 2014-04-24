Meta:
@sprint_22
@us_50.1
@id_s23u50.1s5

Scenario: A scenario that prepares data

Given there is the product with 'SCPBC-name-4' name, 'SCPBC-sku-4' sku, 'SCPBC-barcode-4' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-15days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | SCPBC-sku-4 |
| quantity | 1 |
| price | 145 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-SCPBC'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-14days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | SCPBC-sku-4 |
| quantity | 2 |
| price | 123 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-SCPBC'

Given the user runs the recalculate_metrics cap command
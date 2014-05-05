Meta:
@sprint_22
@us_50.1
@id_s23u50.1s3
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'SCPBC-name-2' name, 'SCPBC-barcode-2' barcode, 'unit' type, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | SCPBC-name-2 |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-SCPBC'
Meta:
@sprint_28
@us_54.5
@smoke
@id_s28u54.5s1

Scenario: A scenario that prepares data

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDateTime | 8:00:00 |
| acceptanceDate | today-1days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 28544 |
| quantity | 1 |
| price | 80 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s28u544'

Given the user prepares yesterday purchases for us 54.4 story

Given the user runs the symfony:reports:recalculate command




Meta:
@sprint_28
@us_56
@id_s28u56s1
@id_s28u56s2
@smoke

Scenario: A scenario that prepares data

Given the user with email 's28u544@lighthouse.pro' creates the store with number '2856'

Given the user with email 's28u544@lighthouse.pro' creates invoice api object with values
| elementName | value |
| acceptanceDateTime | 8:00:00 |
| acceptanceDate | today-1days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-28544 |
| quantity | 12 |
| price | 95 |
And the user with email 's28u544@lighthouse.pro' creates the invoice with invoice builder steps

Given the user prepares yesterday purchases for us 56 story

Given the user runs the symfony:reports:recalculate command



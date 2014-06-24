Meta:
@sprint_28
@us_54.4
@us_54.2
@us_56
@smoke
@id_s28u54.4s1
@id_s28u54.4s2
@id_s28u54.2s1
@id_s28u54.2s2
@id_s28u54.2s3
@id_s28u54.2s4
@id_s28u54.2s5
@id_s28u56s1
@id_s28u56s2

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given the user runs the symfony:user:create command with params: email 's28u544@lighthouse.pro' and password 'lighthouse'

Given the user with email 's28u544@lighthouse.pro' creates the store with number '28544'

And the user with email 's28u544@lighthouse.pro' creates the subCategory with name 'defaultSubCategory-s28u544' related to group named 'defaultGroup-s28u544' and category named 'defaultCategory-s28u544'
And the user with email 's28u544@lighthouse.pro' sets subCategory 'defaultSubCategory-s28u544' mark up with max '10' and min '0' values

Given the user with email 's28u544@lighthouse.pro' creates the product with 'name-28544' name, '28544' barcode, 'unit' type, '100' purchasePrice of group named 'defaultGroup-s28u544', category named 'defaultCategory-s28u544', subcategory named 'defaultSubCategory-s28u544'

Given the user with email 's28u544@lighthouse.pro' creates invoice api object with values
| elementName | value |
| acceptanceDateTime | 8:00:00 |
| acceptanceDate | today-2days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-28544 |
| quantity | 8 |
| price | 90 |
And the user with email 's28u544@lighthouse.pro' creates the invoice with invoice builder steps

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
| quantity | 6 |
| price | 100 |
And the user with email 's28u544@lighthouse.pro' creates the invoice with invoice builder steps

Given the user prepares two days ago purchases for us 54.4 story

Given the user prepares yesterday purchases for us 54.4 story

Given the user runs the symfony:reports:recalculate command



Meta:
@sprint_27
@us_54.1
@id_s27u54.1s2

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s27u541', position 'storeManager-s27u541', username 'storeManager-s27u541', password 'lighthouse', role 'storeManager'
Given there is the user with name 'departmentManager-s27u541', position 'departmentManager-s27u541', username 'departmentManager-s27u541', password 'lighthouse', role 'departmentManager'

Given there is the store with number '27541' managed by 'storeManager-s27u541'
Given there is the store with number '27541' managed by department manager named 'departmentManager-s27u541'

And there is the subCategory with name 'defaultSubCategory-s27u541' related to group named 'defaultGroup-s27u541' and category named 'defaultCategory-s27u541'
And the user sets subCategory 'defaultSubCategory-s27u541' mark up with max '10' and min '0' values

Given there is the product with 'name-27541' name, '27541' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s27u541', category named 'defaultCategory-s27u541', subcategory named 'defaultSubCategory-s27u541'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDateTime | 8:00:00 |
| acceptanceDate | today-2days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 27541 |
| quantity | 50 |
| price | 90 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s27u541'

Given the user prepares yesterday purchases for us 54.1 story

Given the user prepares today purchases for us 54.1 story


Given the user runs the symfony:reports:recalculate command
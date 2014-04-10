Meta:
@sprint_25
@us_52.1
@smoke

Scenario: A scenario that prepares data

Given there is the user with name 'departmentManager-s25u521', position 'departmentManager-s25u521', username 'departmentManager-s25u521', password 'lighthouse', role 'departmentManager'

Given there is the store with number '25521' managed by department manager named 'departmentManager-s25u521'
And there is the subCategory with name 'defaultSubCategory-s25u521' related to group named 'defaultGroup-s25u521' and category named 'defaultCategory-s25u521'
And the user sets subCategory 'defaultSubCategory-s25u521' mark up with max '10' and min '0' values

Given there is the invoice in the store with number '25521' ruled by department manager with name 'departmentManager-s25u521' with values
| elementName | elementValue |
| sku | 25521-01 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |

Given there is the writeOff in the store with number '25521' ruled by department manager with name 'departmentManager-s25u521' with values
| elementName | elementValue |
| number | 25521-01 |
| date | 02.04.2013 |



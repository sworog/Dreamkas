Meta:
@sprint_23
@us_51
@id_s23u51s7

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-6 |
| units | unit |
| vat | 10 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-6 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-15days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-2351-6 |
| quantity | 10 |
| price | 100,00 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u51'

Given the user navigates to the product with name 'name-2351-6'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| vat | 0 |
And the user clicks the create button
When the user logs out

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-15days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-2351-6 |
| quantity | 10 |
| price | 100,00 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u51'
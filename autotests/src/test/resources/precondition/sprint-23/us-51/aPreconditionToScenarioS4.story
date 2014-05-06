Meta:
@sprint_23
@us_51
@id_s23u51s4

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-1 |
| type | unit |
| vat | 0 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-1 |
| vendorCountry |  |
| vendor |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 19.11.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-2351-1 |
| quantity | 4,555 |
| price | 145,50 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u51'
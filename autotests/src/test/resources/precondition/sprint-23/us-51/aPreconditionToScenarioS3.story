Meta:
@sprint_23
@us_51
@id_s23u51s3
@smoke

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351 |
| units | unit |
| vat | 10 |
| purchasePrice | 134,80 |
| barcode | barcode-2351 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-01 |
| units | unit |
| vat | 18 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-01 |
| vendorCountry |  |
| vendor |  |
| info |  |
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
| productName | name-2351 |
| quantity | 7 |
| price | 100 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-2351-01 |
| quantity | 15 |
| price | 120 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u51'
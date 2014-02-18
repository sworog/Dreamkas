Meta:
@sprint_23
@us_51
@id_s23u51s4

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-1 |
| units | unit |
| vat | 0 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-1 |
| sku | sku-2351-1 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

And there is the invoice in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51' with values
| elementName | elementValue |
| sku | invoice-2351-2 |
| acceptanceDate | 19.11.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 19.11.2013 |
And the user adds the product to the invoice with name 'invoice-2351-2' with sku 'sku-2351-1', quantity '4,555', price '145,50' in the store ruled by 'departmentManager-s23u51'
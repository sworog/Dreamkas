Meta:
@sprint 23
@us 51
@id s23u51s3
@smoke

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351 |
| units | unit |
| vat | 10 |
| purchasePrice | 134,80 |
| barcode | barcode-2351 |
| sku | sku-2351 |
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
| sku | sku-2351-01 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

And there is the invoice in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51' with values
| elementName | elementValue |
| sku | invoice-2351-1 |
| acceptanceDate | 19.11.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 19.11.2013 |
And the user adds the product to the invoice with name 'invoice-2351-1' with sku 'sku-2351', quantity '7', price '100,00' in the store ruled by 'departmentManager-s23u51'
And the user adds the product to the invoice with name 'invoice-2351-1' with sku 'sku-2351-01', quantity '15', price '120,00' in the store ruled by 'departmentManager-s23u51'
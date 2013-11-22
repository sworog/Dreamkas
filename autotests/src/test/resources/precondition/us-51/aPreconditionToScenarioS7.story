Meta:
@us 51
@id s23u51s7

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-6 |
| units | unit |
| vat | 10 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-6 |
| sku | sku-2351-6 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

And there is the date invoice with sku 'invoice-2351-6' and date 'today-15days' in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51'
And the user adds the product to the invoice with name 'invoice-2351-6' with sku 'sku-2351-6', quantity '10', price '100,00' in the store ruled by 'departmentManager-s23u51'

Given the user navigates to the product with sku 'sku-2351-6'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| vat | 0 |
And the user clicks the create button
When the user logs out

Given there is the date invoice with sku 'invoice-2351-7' and date 'today-15days' in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51'
And the user adds the product to the invoice with name 'invoice-2351-7' with sku 'sku-2351-6', quantity '10', price '100,00' in the store ruled by 'departmentManager-s23u51'
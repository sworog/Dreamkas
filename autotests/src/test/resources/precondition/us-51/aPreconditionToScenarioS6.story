Meta:
@us 51
@id s23u51s6

Scenario: A scenario that prepares data

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-3 |
| units | unit |
| vat | 18 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-3 |
| sku | sku-2351-3 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |
And there is the date invoice with sku 'invoice-2351-4' and date 'today-15days' in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51'
And the user adds the product to the invoice with name 'invoice-2351-4' with sku 'sku-2351-3', quantity '10', price '100,00' in the store ruled by 'departmentManager-s23u51'

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| elementName | elementValue |
| name | name-2351-4 |
| units | unit |
| vat | 10 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-4 |
| sku | sku-2351-4 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |
And there is the date invoice with sku 'invoice-2351-5' and date 'today-15days' in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51'
And the user adds the product to the invoice with name 'invoice-2351-5' with sku 'sku-2351-4', quantity '10', price '100,00' in the store ruled by 'departmentManager-s23u51'

Given the user navigates to the invoice page with name 'invoice-2351-5'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
When the user clicks on item named 'includesVAT'
Then the user waits for checkBoxPreloader
Then the user checks the checkbox 'includesVAT' is 'unChecked'
Given the user runs the recalculate_metrics cap command
When the user logs out
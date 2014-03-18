Meta:
@smoke
@sprint_30
@us_63

Scenario: A scenario that prepares data

Given there is the product with 'name-3063-2' name, '3063-2' sku, '3063-2' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'

Given there is the writeOff in the store with number 'store-s30u63' ruled by department manager with name 'departmentManager-s30u63' with values
| elementName | elementValue |
| number | 3063-01 |
| date | 02.04.2013 |
And the user adds the product to the write off with number '3063-01' with sku '3063-2', quantity '43', price '123,45, cause 'cause' in the store ruled by 'departmentManager-s30u63'



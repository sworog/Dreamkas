Meta:
@sprint 23
@us 52
@id s23u52s7

Scenario: A scenario that prepares data

Given there is the product with 'name-2352-2' name, 'sku-2352-2' sku, 'barcode-2352-2' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
Given there is the writeOff in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| number | writeOff-2352-3 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'writeOff-2352-3' with sku 'sku-2352-2', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-s23u52'

Meta:
@sprint_23
@us_52
@id_s23u52s2
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'name-2352-1' name, 'barcode-2352-1' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
And there is the writeOff in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| number | writeOff-2352-1 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'writeOff-2352-1' with name 'name-2352-1', quantity '4,671', price '121,34', cause 'Плохо продавался' in the store ruled by 'departmentManager-s23u52'

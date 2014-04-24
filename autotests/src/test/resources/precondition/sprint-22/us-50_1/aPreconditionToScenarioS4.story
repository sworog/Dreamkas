Meta:
@sprint_22
@us_50.1
@id_s23u50.1s4
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'SCPBC-name-3' name, 'SCPBC-barcode-3' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'

Given there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-01 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-01' with name 'SCPBC-name-3', quantity '1', price '12,34', cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
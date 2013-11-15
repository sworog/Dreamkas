Meta:
@smoke
@us 48
@id s22u48s1
@id s22u48s2
@id s22u48s3
@id s22u48s4
@id s22u48s5

Scenario: Precondition scenario
Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-11' name, 'SCPBC-sku-11' sku, 'SCPBC-barcode-11' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-11 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-11' with sku 'SCPBC-sku-11', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-12 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-12' with sku 'SCPBC-sku-11', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
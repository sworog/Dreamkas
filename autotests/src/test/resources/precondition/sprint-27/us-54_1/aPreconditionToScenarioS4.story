Meta:
@sprint 27
@us 54.1
@id s21us54.1s4

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s27u541', position 'storeManager-s27u541', username 'storeManager-s27u541', password 'lighthouse', role 'storeManager'
Given there is the user with name 'departmentManager-s27u541', position 'departmentManager-s27u541', username 'departmentManager-s27u541', password 'lighthouse', role 'departmentManager'

Given there is the store with number '27541' managed by 'storeManager-s27u541'
Given there is the store with number '27541' managed by department manager named 'departmentManager-s27u541'

And there is the subCategory with name 'defaultSubCategory-s27u541' related to group named 'defaultGroup-s27u541' and category named 'defaultCategory-s27u541'
And the user sets subCategory 'defaultSubCategory-s27u541' mark up with max '10' and min '0' values

Given there is the product with 'name-27541' name, '27541' sku, '27541' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s27u541', category named 'defaultCategory-s27u541', subcategory named 'defaultSubCategory-s27u541'
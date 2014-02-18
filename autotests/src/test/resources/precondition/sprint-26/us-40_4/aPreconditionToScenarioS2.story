Meta:
@sprint_26
@us_40.4
@smoke
@id_s26u40.4.s2

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'departmentManager-s26u404', position 'departmentManager-s26u404', username 'departmentManager-s26u404', password 'lighthouse', role 'departmentManager'

Given there is the store with number '26404' managed by department manager named 'departmentManager-s26u404'
And there is the subCategory with name 'defaultSubCategory-s26u404' related to group named 'defaultGroup-s26u404' and category named 'defaultCategory-s26u404'
And the user sets subCategory 'defaultSubCategory-s26u404' mark up with max '10' and min '0' values

Given there is the product with 'name-26404' name, '26404' sku, '26404' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s26u404', category named 'defaultCategory-s26u404', subcategory named 'defaultSubCategory-s26u404'
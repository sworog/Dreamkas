Meta:
@smoke
@sprint_30
@us_63
@id_s30u63s1
@id_s30u63s2
@id_s30u63s3
@id_s30u63s4
@id_s30u63s5
@id_s30u63s8
@id_s30u63s9
@id_s30u63s10

Scenario: A scenario that prepares data

Given there is the supplier with name 'supplier-s30u63s1'

And there is the subCategory with name 'defaultSubCategory-s30u63' related to group named 'defaultGroup-s30u63' and category named 'defaultCategory-s30u63'
And the user sets subCategory 'defaultSubCategory-s30u63' mark up with max '10' and min '0' values

Given there is the product with 'name-3063' name, '3063' sku, '3063' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'

Given there is the user with name 'departmentManager-s30u63', position 'departmentManager-s30u63', username 'departmentManager-s30u63', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u63' managed by department manager named 'departmentManager-s30u63'



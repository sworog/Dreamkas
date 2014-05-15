Meta:
@smoke
@sprint_30
@us_64
@id_s30u64s2

Scenario: A scenario that prepares data

Given there is the supplier with name 'supplier-s30u64s1'

And there is the subCategory with name 'defaultSubCategory-s30u64' related to group named 'defaultGroup-s30u64' and category named 'defaultCategory-s30u64'
And the user sets subCategory 'defaultSubCategory-s30u64' mark up with max '10' and min '0' values

Given there is the product with 'name-3064' name, '3064' barcode, 'unit' type, '100' purchasePrice of group named 'defaultGroup-s30u64', category named 'defaultCategory-s30u64', subcategory named 'defaultSubCategory-s30u64'

Given there is the order in the store by 'departmentManager-s30u64'

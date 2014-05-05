Meta:
@smoke
@sprint_32
@us_68.1

Scenario: A scenario that prepares data supplier and product

Given there is the supplier with name 'supplier-s32u681s1'

Given there is the subCategory with name 'defaultSubCategory-s32u681' related to group named 'defaultGroup-s32u681' and category named 'defaultCategory-s32u681'
And the user sets subCategory 'defaultSubCategory-s32u681' mark up with max '10' and min '0' values

Given there is the product with 'name-32681' name, '32681' barcode, 'unit' type, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

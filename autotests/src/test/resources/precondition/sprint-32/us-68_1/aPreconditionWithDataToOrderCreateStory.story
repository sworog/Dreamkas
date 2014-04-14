Meta:
@smoke
@sprint_31
@us_68.1

Scenario: A scenario that prepares data supplier and product

Given there is the supplier with name 'supplier-s31u681s1'

Given there is the subCategory with name 'defaultSubCategory-s31u681' related to group named 'defaultGroup-s31u681' and category named 'defaultCategory-s31u681'
And the user sets subCategory 'defaultSubCategory-s31u681' mark up with max '10' and min '0' values

Given there is the product with 'name-31681' name, '31681' sku, '31681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s31u681', category named 'defaultCategory-s31u681', subcategory named 'defaultSubCategory-s31u681'

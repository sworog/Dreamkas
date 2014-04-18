Meta:
@smoke
@sprint_32
@us_68
@id_s32u68s1
@id_s32u68s2
@id_s32u68s3
@id_s32u68s4

Scenario: A scenario that prepares data supplier and product

Given there is the supplier with name 'supplier-s32u68s1'

Given there is the subCategory with name 'defaultSubCategory-s32u68' related to group named 'defaultGroup-s32u68' and category named 'defaultCategory-s32u68'
And the user sets subCategory 'defaultSubCategory-s32u68' mark up with max '10' and min '0' values

Given there is the product with 'name-3268' name, '3268' sku, '3268' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u68', category named 'defaultCategory-s32u68', subcategory named 'defaultSubCategory-s32u68'
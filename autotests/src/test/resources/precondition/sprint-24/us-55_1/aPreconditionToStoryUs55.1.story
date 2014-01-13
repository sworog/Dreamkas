Meta:
@sprint 24
@us 55.1
@smoke
@id s24u55.1s1
@id s24u55.1s2
@id s24u55.1s3
@id s24u55.1s4
@id s24u55.1s5
@id s24u55.1s6

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is created store with number '245521', address '245521', contacts '245521'
And there is created store with number '245522', address '245522', contacts '245522'

Given there is the subCategory with name 'defaultSubCategory-s24u552' related to group named 'defaultGroup-s24u552' and category named 'defaultCategory-s24u552'
And the user sets subCategory 'defaultSubCategory-s24u552' mark up with max '10' and min '0' values

Given there is the product with 'name-24552' name, '24552' sku, '24552' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s24u552', category named 'defaultCategory-s24u552', subcategory named 'defaultSubCategory-s24u552'
Given there is the product with 'name-245522' name, '245522' sku, '245522' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s24u552', category named 'defaultCategory-s24u552', subcategory named 'defaultSubCategory-s24u552'
Meta:
@sprint_24
@us_55.2
@smoke
@id_s24u55.2s1
@id_s24u55.2s2
@id_s24u55.2s3
@id_s24u55.2s4
@id_s24u55.2s5
@id_s24u55.2s6
@id_s24u55.2s7
@id_s24u55.2s8

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is created store with number '245521', address '245521', contacts '245521'
And there is created store with number '245522', address '245522', contacts '245522'

Given there is the subCategory with name 'defaultSubCategory-s24u552' related to group named 'defaultGroup-s24u552' and category named 'defaultCategory-s24u552'
And the user sets subCategory 'defaultSubCategory-s24u552' mark up with max '10' and min '0' values

Given there is the product with 'name-24552' name, '24552' barcode, 'unit' type, '124,5' purchasePrice of group named 'defaultGroup-s24u552', category named 'defaultCategory-s24u552', subcategory named 'defaultSubCategory-s24u552'
Given there is the product with 'name-245522' name, '245522' barcode, 'unit' type, '124,5' purchasePrice of group named 'defaultGroup-s24u552', category named 'defaultCategory-s24u552', subcategory named 'defaultSubCategory-s24u552'
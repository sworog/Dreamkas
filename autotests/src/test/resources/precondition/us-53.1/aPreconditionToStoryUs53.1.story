Meta:
@sprint 25
@us 53.1
@smoke
@id s24u53.1s1
@id s24u53.1s2
@id s24u53.1s3
@id s24u53.1s4
@id s24u53.1s5
@id s24u53.1s6
@id s24u53.1s7
@id s24u53.1s8
@id s24u53.1s9
@id s24u53.1s10
@id s24u53.1s11
@id s24u53.1s12
@id s24u53.1s13
@id s24u53.1s14

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s25u531', position 'storeManager-s25u531', username 'storeManager-s25u531', password 'lighthouse', role 'storeManager'

And there is the store with number '24531' managed by 'storeManager-s25u531'
And there is the subCategory with name 'defaultSubCategory-s25u531' related to group named 'defaultGroup-s25u531' and category named 'defaultCategory-s25u531'
And the user sets subCategory 'defaultSubCategory-s25u531' mark up with max '10' and min '0' values

Given there is the product with 'name-24531' name, '24531' sku, '24531' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u531', category named 'defaultCategory-s25u531', subcategory named 'defaultSubCategory-s25u531'
And there is the product with 'name-245311' name, '245311' sku, '245311' barcode, 'unit' units, '174,5' purchasePrice of group named 'defaultGroup-s25u531', category named 'defaultCategory-s25u531', subcategory named 'defaultSubCategory-s25u531'
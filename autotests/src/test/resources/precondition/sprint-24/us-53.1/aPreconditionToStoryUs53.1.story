Meta:
@sprint_24
@us_53.1
@smoke
@id_s24u53.1s1
@id_s24u53.1s2
@id_s24u53.1s3
@id_s24u53.1s4
@id_s24u53.1s5
@id_s24u53.1s6
@id_s24u53.1s7
@id_s24u53.1s8
@id_s24u53.1s9
@id_s24u53.1s10
@id_s24u53.1s11
@id_s24u53.1s12
@id_s24u53.1s13
@id_s24u53.1s14

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s24u531', position 'storeManager-s24u531', username 'storeManager-s24u531', password 'lighthouse', role 'storeManager'

And there is the store with number '24531' managed by 'storeManager-s24u531'
And there is the subCategory with name 'defaultSubCategory-s24u531' related to group named 'defaultGroup-s24u531' and category named 'defaultCategory-s24u531'
And the user sets subCategory 'defaultSubCategory-s24u531' mark up with max '10' and min '0' values

Given there is the product with 'name-24531' name, '24531' sku, '24531' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s24u531', category named 'defaultCategory-s24u531', subcategory named 'defaultSubCategory-s24u531'
And there is the product with 'name-245311' name, '245311' sku, '245311' barcode, 'unit' units, '174,5' purchasePrice of group named 'defaultGroup-s24u531', category named 'defaultCategory-s24u531', subcategory named 'defaultSubCategory-s24u531'
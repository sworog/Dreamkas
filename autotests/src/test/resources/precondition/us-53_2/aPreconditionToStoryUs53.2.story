Meta:
@sprint 24
@us 53.2
@smoke
@id s24us532s1
@id s24us532s2
@id s24us532s3
@id s24us532s4
@id s24us532s5
@id s24us532s6
@id s24us532s7
@id s24us532s8

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s24u532', position 'storeManager-s24u532', username 'storeManager-s24u532', password 'lighthouse', role 'storeManager'

And there is the store with number '24531' managed by 'storeManager-s24u532'
And there is the subCategory with name 'defaultSubCategory-s24u532' related to group named 'defaultGroup-s24u532' and category named 'defaultCategory-s24u532'
And the user sets subCategory 'defaultSubCategory-s24u532' mark up with max '10' and min '0' values

Given there is the product with 'name-24531' name, '24531' sku, '24531' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s24u532', category named 'defaultCategory-s24u532', subcategory named 'defaultSubCategory-s24u532'
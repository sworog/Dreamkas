Meta:
@sprint_25
@us_57.3
@id_s25u57.3s7
@smoke

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s25u573', position 'storeManager-s25u573', username 'storeManager-s25u573', password 'lighthouse', role 'storeManager'
Given there is the user with name 'storeManager-s25u5731', position 'storeManager-s25u5731', username 'storeManager-s25u5731', password 'lighthouse', role 'storeManager'

Given there is the store with number '25573' managed by 'storeManager-s25u573'
Given there is the store with number '255731' managed by 'storeManager-s25u5731'

And there is the subCategory with name 'defaultSubCategory-s25u573' related to group named 'defaultGroup-s25u573' and category named 'defaultCategory-s25u573'
And there is the subCategory with name 'defaultSubCategory-s25u5731' related to group named 'defaultGroup-s25u5731' and category named 'defaultCategory-s25u5731'
And the user sets subCategory 'defaultSubCategory-s25u573' mark up with max '10' and min '0' values
And the user sets subCategory 'defaultSubCategory-s25u5731' mark up with max '10' and min '0' values

Given there is the product with 'name-25573' name, '25573' barcode, 'unit' units, '100,0' purchasePrice of group named 'defaultGroup-s25u573', category named 'defaultCategory-s25u573', subcategory named 'defaultSubCategory-s25u573'
Given there is the product with 'name-255731' name, '255731' barcode, 'unit' units, '100,0' purchasePrice of group named 'defaultGroup-s25u5731', category named 'defaultCategory-s25u5731', subcategory named 'defaultSubCategory-s25u5731'
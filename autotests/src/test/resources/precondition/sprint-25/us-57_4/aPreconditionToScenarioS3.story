Meta:
@sprint_25
@us_57.4
@id_s25u57.4s3
@smoke

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s25u574', position 'storeManager-s25u574', username 'storeManager-s25u574', password 'lighthouse', role 'storeManager'
And there is the user with name 'storeManager-s25u5742', position 'storeManager-s25u5742', username 'storeManager-s25u5742', password 'lighthouse', role 'storeManager'

Given there is the store with number '25574' managed by 'storeManager-s25u574'
And there is the store with number '255742' managed by 'storeManager-s25u5742'

And there is the subCategory with name 'defaultSubCategory-s25u574' related to group named 'defaultGroup-s25u574' and category named 'defaultCategory-s25u574'
And the user sets subCategory 'defaultSubCategory-s25u574' mark up with max '10' and min '0' values

And there is the subCategory with name 'defaultSubCategory-s25u5742' related to group named 'defaultGroup-s25u5742' and category named 'defaultCategory-s25u5742'
And the user sets subCategory 'defaultSubCategory-s25u5742' mark up with max '10' and min '0' values


Given there is the product with 'name-255742' name, '255742' sku, '255742' barcode, 'unit' units, '100,0' purchasePrice of group named 'defaultGroup-s25u574', category named 'defaultCategory-s25u574', subcategory named 'defaultSubCategory-s25u574'
And there is the product with 'name-255743' name, '255743' sku, '255743' barcode, 'unit' units, '100,0' purchasePrice of group named 'defaultGroup-s25u5742', category named 'defaultCategory-s25u5742', subcategory named 'defaultSubCategory-s25u5742'

Given the user prepares data for us 57.4 story
Given the user runs the symfony:reports:recalculate command
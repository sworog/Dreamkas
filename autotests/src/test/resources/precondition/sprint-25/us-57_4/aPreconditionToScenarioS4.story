Meta:
@sprint_25
@us_57.4
@smoke
@id_s25u57.4s4
@id_s25u57.4s5
@id_s25u57.4s6
@id_s25u57.4s7
@id_s25u57.4s8

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s25u574', position 'storeManager-s25u574', username 'storeManager-s25u574', password 'lighthouse', role 'storeManager'

Given there is the store with number '25574' managed by 'storeManager-s25u574'

And there is the subCategory with name 'defaultSubCategory-s25u574' related to group named 'defaultGroup-s25u574' and category named 'defaultCategory-s25u574'
And the user sets subCategory 'defaultSubCategory-s25u574' mark up with max '10' and min '0' values

Given there is the product with 'name-255742' name, '255742' sku, '255742' barcode, 'unit' units, '100,0' purchasePrice of group named 'defaultGroup-s25u574', category named 'defaultCategory-s25u574', subcategory named 'defaultSubCategory-s25u574'
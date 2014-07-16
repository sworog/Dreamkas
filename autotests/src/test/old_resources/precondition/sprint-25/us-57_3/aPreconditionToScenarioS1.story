Meta:
@sprint_25
@us_57.3
@id_s25u57.3s1
@id_s25u57.3s2
@id_s25u57.3s3
@id_s25u57.3s4
@id_s25u57.3s5
@id_s25u57.3s6
@smoke

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the store with number '25573'

And there is the subCategory with name 'defaultSubCategory-s25u573' related to group named 'defaultGroup-s25u573' and category named 'defaultCategory-s25u573'
And the user sets subCategory 'defaultSubCategory-s25u573' mark up with max '10' and min '0' values

Given there is the product with 'name-25573' name, '25573' barcode, 'unit' type, '100,0' purchasePrice of group named 'defaultGroup-s25u573', category named 'defaultCategory-s25u573', subcategory named 'defaultSubCategory-s25u573'

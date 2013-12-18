Meta:
@sprint 25
@us 57.4
@id s25u57.4s1
@id s25u57.4s2

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s25u574', position 'storeManager-s25u574', username 'storeManager-s25u574', password 'lighthouse', role 'storeManager'

Given there is the store with number '25574' managed by 'storeManager-s25u574'
And there is the subCategory with name 'defaultSubCategory-s25u574' related to group named 'defaultGroup-s25u574' and category named 'defaultCategory-s25u574'
And the user sets subCategory 'defaultSubCategory-s25u574' mark up with max '10' and min '0' values
Meta:
@smoke
@sprint_31
@us_63.1
@id_s31u63.1s1
@id_s31u63.1s2

Scenario: A scenario that prepares data

Given there is the supplier with name 'supplier-s30u631s1'

Given there is the subCategory with name 'defaultSubCategory-s30u631' related to group named 'defaultGroup-s30u631' and category named 'defaultCategory-s30u631'
And the user sets subCategory 'defaultSubCategory-s30u631' mark up with max '10' and min '0' values

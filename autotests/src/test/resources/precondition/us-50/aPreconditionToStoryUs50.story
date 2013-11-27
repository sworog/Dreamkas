Meta:
@smoke
@us 50
@id s23u50s1
@id s23u50s2
@id s23u50s3
@id s23u50s4
@id s23u50s5
@id s23u50s6
@id s23u50s7
@id s23u50s8
@id s23u50s9

Scenario: A scenario that prepares data

Given there is the user with name 'departmentManager-s23u50', position 'departmentManager-s23u50', username 'departmentManager-s23u50', password 'lighthouse', role 'departmentManager'
And there is the store with number '2350' managed by department manager named 'departmentManager-s23u50'
And there is the subCategory with name 'defaultSubCategory-s23u50' related to group named 'defaultGroup-s23u50' and category named 'defaultCategory-s23u50'
And the user sets subCategory 'defaultSubCategory-s23u50' mark up with max '10' and min '0' values
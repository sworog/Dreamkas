Meta:
@us 52
@id s23u52s1
@id s23u52s2
@id s23u52s3
@id s23u52s4
@id s23u52s5
@id s23u52s6
@id s23u52s7

Scenario: A scenario that prepares data

Given there is the user with name 'departmentManager-s23u52', position 'departmentManager-s23u52', username 'departmentManager-s23u52', password 'lighthouse', role 'departmentManager'
And there is the store with number '2352' managed by department manager named 'departmentManager-s23u52'
And there is the subCategory with name 'defaultSubCategory-s23u52' related to group named 'defaultGroup-s23u52' and category named 'defaultCategory-s23u52'
And the user sets subCategory 'defaultSubCategory-s23u52' mark up with max '10' and min '0' values
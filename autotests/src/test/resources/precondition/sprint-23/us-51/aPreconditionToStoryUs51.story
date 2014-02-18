Meta:
@sprint_23
@us_51
@id_s23u51s1
@id_s23u51s2
@id_s23u51s3
@id_s23u51s4
@id_s23u51s5
@id_s23u51s6
@moke

Scenario: A scenario that prepares data

Given there is the user with name 'departmentManager-s23u51', position 'departmentManager-s23u51', username 'departmentManager-s23u51', password 'lighthouse', role 'departmentManager'
And there is the store with number '2351' managed by department manager named 'departmentManager-s23u51'
And there is the subCategory with name 'defaultSubCategory-s23u51' related to group named 'defaultGroup-s23u51' and category named 'defaultCategory-s23u51'
And the user sets subCategory 'defaultSubCategory-s23u51' mark up with max '10' and min '0' values
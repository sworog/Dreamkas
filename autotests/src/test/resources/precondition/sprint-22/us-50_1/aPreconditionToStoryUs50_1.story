Meta:
@sprint 22
@smoke
@us 50.1
@id s22u50.1s1
@id s22u50.1s2
@id s22u50.1s3
@id s22u50.1s4
@id s22u50.1s5
@id s22u50.1s6

Scenario: A scenario that prepares data

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
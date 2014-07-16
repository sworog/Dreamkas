Meta:
@sprint_22
@smoke
@us_48
@id_s22u48s1
@id_s22u48s2
@id_s22u48s3
@id_s22u48s4
@id_s22u48s5

Scenario: A step that prepares data

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
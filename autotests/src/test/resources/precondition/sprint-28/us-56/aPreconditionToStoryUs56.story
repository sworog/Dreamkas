Meta:
@sprint 28
@us 56
@id s28u56s1
@id s28u56s2
@smoke

Scenario: A scenario that prepares data

Given there is the user with name 'storeManager-s28u56', position 'storeManager-s28u56', username 'storeManager-s28u56', password 'lighthouse', role 'storeManager'
Given there is the user with name 'departmentManager-s28u56', position 'departmentManager-s28u56', username 'departmentManager-s28u56', password 'lighthouse', role 'departmentManager'

Given there is the store with number '2856' managed by 'storeManager-s28u56'
Given there is the store with number '2856' managed by department manager named 'departmentManager-s28u56'

And there is the subCategory with name 'defaultSubCategory-s28u544' related to group named 'defaultGroup-s28u544' and category named 'defaultCategory-s28u544'
And the user sets subCategory 'defaultSubCategory-s28u544' mark up with max '10' and min '0' values

Given there is the date invoice with sku 'Invoice-2856' and date 'today-1days' and time set to '8:00:00' in the store with number '2856' ruled by department manager with name 'departmentManager-s28u56'
And the user adds the product to the invoice with name 'Invoice-2856' with sku '28544', quantity '12', price '95' in the store ruled by 'departmentManager-s28u56'

Given the user prepares yesterday purchases for us 56 story

Given the user runs the symfony:reports:recalculate command



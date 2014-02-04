Meta:
@sprint 28
@us 54.4
@us 54.2
@smoke
@id s28u54.4s1
@id s28u54.4s2
@id s28u54.2s1
@id s28u54.2s2
@id s28u54.2s3
@id s28u54.2s4
@id s28u54.2s5

Scenario: A scenario that prepares data

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s28u544', position 'storeManager-s28u544', username 'storeManager-s28u544', password 'lighthouse', role 'storeManager'
Given there is the user with name 'departmentManager-s28u544', position 'departmentManager-s28u544', username 'departmentManager-s28u544', password 'lighthouse', role 'departmentManager'

Given there is the store with number '28544' managed by 'storeManager-s28u544'
Given there is the store with number '28544' managed by department manager named 'departmentManager-s28u544'

And there is the subCategory with name 'defaultSubCategory-s28u544' related to group named 'defaultGroup-s28u544' and category named 'defaultCategory-s28u544'
And the user sets subCategory 'defaultSubCategory-s28u544' mark up with max '10' and min '0' values

Given there is the product with 'name-28544' name, '28544' sku, '28544' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s28u544', category named 'defaultCategory-s28u544', subcategory named 'defaultSubCategory-s28u544'

Given there is the date invoice with sku 'Invoice-28544' and date 'today-2days' and time set to '8:00:00' in the store with number '28544' ruled by department manager with name 'departmentManager-s28u544'
And the user adds the product to the invoice with name 'Invoice-28544' with sku '28544', quantity '8', price '90' in the store ruled by 'departmentManager-s28u544'

Given there is the date invoice with sku 'Invoice-28544-1' and date 'today-1days' and time set to '8:00:00' in the store with number '28544' ruled by department manager with name 'departmentManager-s28u544'
And the user adds the product to the invoice with name 'Invoice-28544-1' with sku '28544', quantity '6', price '100' in the store ruled by 'departmentManager-s28u544'

Given the user prepares two days ago purchases for us 54.4 story

Given the user prepares yesterday purchases for us 54.4 story

Given the user runs the symfony:reports:recalculate command



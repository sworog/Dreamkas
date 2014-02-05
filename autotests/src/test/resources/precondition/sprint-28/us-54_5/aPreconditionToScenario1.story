Meta:
@sprint 28
@us 54.5
@smoke
@id s28u54.5s1

Scenario: A scenario that prepares data

Given there is the date invoice with sku 'Invoice-28544-1' and date 'today-1days' and time set to '8:00:00' in the store with number '28544' ruled by department manager with name 'departmentManager-s28u544'
And the user adds the product to the invoice with name 'Invoice-28544-1' with sku '28544', quantity '1', price '80' in the store ruled by 'departmentManager-s28u544'

Given the user prepares yesterday purchases for us 54.4 story

Given the user runs the symfony:reports:recalculate command




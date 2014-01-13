Meta:
@sprint 24
@us 53.2

Narrative:
As a директор магазина
I want to видеть сумму продаж своего магазина по часам в сравнении с суммой продаж по часам за вчера и за день неделю назад
In order to понять в каком часу произошел провал продаж и принять меры на будущее.

Scenario: The store gross sale by hour report dont contain data on current hour

Meta:
@id s24us532s1
@description the store gross sale by hour report dont contain data on current hour
@smoke

GivenStories: precondition/sprint-24/us-53_2/aPreconditionToStoryUs53.2.story

Given the user prepares data for us 53.2 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u532' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by hour report link
Then the user checks the store gross sale by hour report dont contain data on current hour

Scenario: The user checks the store gross sale by hour report contains correct data

Meta:
@id s24us532s2
@description the user checks the store gross sale by hour report contains correct data
@smoke

GivenStories: precondition/sprint-24/us-53_2/aPreconditionToStoryUs53.2.story

Given the user prepares data for us 53.2 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u532' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by hour report link
Then the user checks the store gross sale by hour report contains correct data

Scenario: Can't see reports navigation menu link for watchman

Meta:
@id s24us532s3
@description no reports navigation menu link for admin

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the reports navigation menu item is not visible

Scenario: Can't see reports navigation menu link for departmentManager

Meta:
@id s24us532s4
@description no reports navigation menu link for departmentManager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the reports navigation menu item is not visible

Scenario: Can't navigate directly to reports page for watchman

Meta:
@id s24us532s5
@description get 403 navigating directly to reports page for watchman

GivenStories: precondition/sprint-24/us-53_2/aPreconditionToStoryUs53.2.story

Given the user opens gross sale by hours report page of store number '24531'
And the user logs in as 'watchman'
Then the user sees the 403 error

Scenario: Can't navigate directly to reports page for departmentManager

Meta:
@id s24us532s6
@description get 403 navigating directly to reports page for departmentManager

GivenStories: precondition/sprint-24/us-53_2/aPreconditionToStoryUs53.2.story

Given the user opens gross sale by hours report page of store number '24531'
And the user logs in as 'departmentManager'
Then the user sees the 403 error
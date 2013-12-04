Meta:
@sprint 24
@us 53.2

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: The store gross sale by hour report dont contain data on current hour

Meta:
@id s24us532s1
@description the store gross sale by hour report dont contain data on current hour
@smoke

GivenStories: precondition/us-53_2/aPreconditionToStoryUs53.2.story

Given the user prepares data for us 53.2 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u532' userName and 'lighthouse' password
And the user clicks the menu report item
Then the user checks the store gross sale by hour report dont contain data on current hour

Scenario: The user checks the store gross sale by hour report contains correct data

Meta:
@id s24us532s2
@description the user checks the store gross sale by hour report contains correct data
@smoke

GivenStories: precondition/us-53_2/aPreconditionToStoryUs53.2.story

Given the user prepares data for us 53.2 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u532' userName and 'lighthouse' password
And the user clicks the menu report item
Then the user checks the store gross sale by hour report contains correct data


Scenario: Can't see reports navigation menu link for commercialManager

Meta:
@id s24us532s3
@description no reports navigation menu link for commercialManager

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the reports navigation menu item is not visible

Scenario: Can't see reports navigation menu link for watchman

Meta:
@id s24us532s4
@description no reports navigation menu link for admin

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the reports navigation menu item is not visible

Scenario: Can't see reports navigation menu link for departmentManager

Meta:
@id s24us532s5
@description no reports navigation menu link for departmentManager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the reports navigation menu item is not visible

Scenario: Can't navigate directly to reports page for commercialManager

Meta:
@id s24us532s6
@description get 403 navigating directly to reports page for commercialManager

Given the user opens gross sale by hour report page
And the user logs in as 'commercialManager'
Then the user sees the 403 error

Scenario: Can't navigate directly to reports page for watchman

Meta:
@id s24us532s7
@description get 403 navigating directly to reports page for watchman

Given the user opens gross sale by hour report page
And the user logs in as 'watchman'
Then the user sees the 403 error

Scenario: Can't navigate directly to reports page for departmentManager

Meta:
@id s24us532s8
@description get 403 navigating directly to reports page for departmentManager

Given the user opens gross sale by hour report page
And the user logs in as 'departmentManager'
Then the user sees the 403 error
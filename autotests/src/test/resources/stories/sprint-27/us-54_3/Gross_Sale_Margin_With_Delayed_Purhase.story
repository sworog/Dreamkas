Meta:
Meta:
@sprint_27
@us_54.3

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Gross Sale margin values checking if delayed late purchase is come

Meta:
@id_s27u54.3s1
@description_@smoke

GivenStories: precondition/sprint-27/us-54_1/aPreconditionToStoryUs54.1.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s27u541' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the report name is 'Валовая прибыль. Магазин №27541'
And the user checks the gross sale margin table contains expected value entries

Given the user prepares yesterday delayed purchase for us 54.3 story
And the user runs the symfony:reports:recalculate command
When the user refreshes the current page

Then the user checks the gross sale margin table with delayed purchase contains expected value entries

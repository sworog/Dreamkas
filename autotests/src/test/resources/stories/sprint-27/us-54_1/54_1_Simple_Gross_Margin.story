Meta:
@sprint 27
@us 54.1

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Store gross sale margin verification scenario

Meta:
@id s21us54.1s1
@description
@smoke

GivenStories: precondition/sprint-27/us-54_1/aPreconditionToStoryUs54.1.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s27u541' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the report name is 'Валовая прибыль. Магазин №27541'
And the user checks the gross sale margin table contains expected value entries
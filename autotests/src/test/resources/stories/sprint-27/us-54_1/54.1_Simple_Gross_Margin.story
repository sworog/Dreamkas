Meta:
@sprint 27
@us 54.1

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Store gross sale margin verification scenario

Meta:
@id s27u54.1s1
@description
@smoke

GivenStories: precondition/sprint-27/us-54_1/aPreconditionToStoryUs54.1.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s27u541' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the report name is 'Валовая прибыль. Магазин №27541'
And the user checks the gross sale margin table contains expected value entries

Scenario: Store today gross margin is not shown if sales are registered today

Meta:
@id s27u54.1s2
@description

GivenStories: precondition/sprint-27/us-54_1/aPreconditionToScenarioS2.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s27u541' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks there is no gross sale margin table with today date

Scenario: No sales - show null gross sale margin values

Meta:
@id s27u54.1s3
@description

GivenStories: precondition/sprint-27/us-54_1/aPreconditionToScenarioS3.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s27u541' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected five days ago entries

Scenario: No data shown if there is no data at all

Meta:
@id s27u54.1s4
@description

GivenStories: precondition/sprint-27/us-54_1/aPreconditionToScenarioS4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s27u541' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks page contains text 'Нет данных'


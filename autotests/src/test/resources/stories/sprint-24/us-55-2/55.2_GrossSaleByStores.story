Meta:
@sprint 24
@us 55.2

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Gross sale report page has correct data

Meta:
@id s24u55.2s1
@description gross sale report page has correct data
@smoke

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story

Given the user prepares data for us 55.2 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user clicks the menu report item
And the user clicks on gross sale by stores report link
Then the user checks the gross sale by stores report has correct data

Scenario: Gross sale report page has zero sales (no sales registered)

Meta:
@id s24u55.2s2
@description gross sale report page has zero sales (no sales registered)

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user clicks the menu report item
And the user clicks on gross sale by stores report link
Then the user checks the gross sale by stores report has zero sales

Scenario: Gross sale by stores yesterday table row name check

Meta:
@id s24u55.2s3
@description check yesterday table row name

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story

Given the user opens gross sale by stores report page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by stores report table yesterday row name

Scenario: Gross sale by stores two days table row name check

Meta:
@id s24u55.2s4
@description check two days table row name

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story

Given the user opens gross sale by stores report page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by stores report table two days ago row name

Scenario: Gross sale by stores eight days table row name check

Meta:
@id s24u55.2s5
@description check eight days table row name

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story

Given the user opens gross sale by stores report page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by stores report table eight days ago row name

Scenario: Gross sale by stores yesterday table row sort check

Meta:
@id s24u55.2s6
@description yesterday table row sort check

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story,
              precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2-S.story

Given the user opens gross sale by stores report page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by stores report table yesterday row sort

Scenario: Gross sale by stores two days table row sort check

Meta:
@id s24u55.2s7
@description eight days table row sort check

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story,
              precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2-S.story

Given the user opens gross sale by stores report page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by stores report table two days ago row sort

Scenario: Gross sale by stores eight days table row sort check

Meta:
@id s24u55.2s8
@description table row sort check

GivenStories: precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2.story,
              precondition/sprint-24/us-55_2/aPreconditionToStoryUs55.2-S.story

Given the user opens gross sale by stores report page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by stores report table eight days ago row sort

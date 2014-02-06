Meta:
@sprint 28
@us 56

Narrative:
As a коммерческий директор
I want to видель валовую прибыль торговой сети по дням
In order to чтобы понимать выполняет ли сеть норму прибыли

Scenario: Gross margin by days

Meta:
@id s28u56s1
@description
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story,
              precondition/sprint-28/us-56/aPreconditionToStoryUs56.story

Given the user opens the authorization page
And the user logs in as 'commercialManager'

When the user clicks the menu report item
And the user clicks on gross margin by days report link

Then the user checks the gross sale margin table contains expected value entries for story 56

Scenario: Gross margin by days no data

Meta:
@id s28u56s2
@description

Given the user runs the symfony:env:init command

Given the user opens the authorization page
And the user logs in as 'commercialManager'

When the user clicks the menu report item
And the user clicks on gross margin by days report link

Then the user checks page contains text 'Нет данных'


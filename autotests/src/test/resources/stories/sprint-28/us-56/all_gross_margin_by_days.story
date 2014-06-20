Meta:
@sprint_28
@us_56

Narrative:
As a коммерческий директор
I want to видель валовую прибыль торговой сети по дням
In order to чтобы понимать выполняет ли сеть норму прибыли

Scenario: Gross margin by days

Meta:
@id_s28u56s1
@description_
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story,
              precondition/sprint-28/us-56/aPreconditionToStoryUs56.story

Given the user opens the authorization page
And the user logs in using 's28u544@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu report item
And the user clicks on gross margin by days report link

Then the user checks the gross sale margin table contains expected value entries for story 56

Scenario: Gross margin by days no data

Meta:
@id_s28u56s2
@description

Given the user runs the symfony:env:init command

Given the user runs the symfony:user:create command with params: email 's28u544@lighthouse.pro' and password 'lighthouse'

Given the user with email 's28u544@lighthouse.pro' creates the store with number '28544'

Given the user opens the authorization page
And the user logs in using 's28u544@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu report item
And the user clicks on gross margin by days report link

Then the user checks page contains text 'Нет данных'


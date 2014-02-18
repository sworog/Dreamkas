Meta:
@sprint_24
@us_55.1

Narrative:
As a коммерческий директор сети
I want to видесть сумму продаж всех магазинов сети за вчера, позавчера и неделю назад +1 день
In order to понять динамику изменения продаж

Scenario: Gross sale by network yesterday value check

Meta:
@id_s24u55.1s1
@description gross sale by network yesterday value check
@smoke

GivenStories: precondition/sprint-24/us-55_1/aPreconditionToStoryUs55.1.story

Given the user prepares data for us 55.1 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by network yesterday value

Scenario: Gross sale by network two days ago value check

Meta:
@id_s24u55.1s2
@description gross sale by network two days ago value check
@smoke

GivenStories: precondition/sprint-24/us-55_1/aPreconditionToStoryUs55.1.story

Given the user prepares data for us 55.1 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by network two days value

Scenario: Gross sale by network eight days ago value check

Meta:
@id_s24u55.1s3
@description gross sale by network eight days ago value check
@smoke

GivenStories: precondition/sprint-24/us-55_1/aPreconditionToStoryUs55.1.story

Given the user prepares data for us 55.1 story
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by network eight days value

Scenario: Gross sale by network yesterday value is zero (no sales registered)

Meta:
@id_s24u55.1s4
@description gross sale by network yesterday value is zero (no sales registered)

GivenStories: precondition/sprint-24/us-55_1/aPreconditionToStoryUs55.1.story

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by network yesterday value is zero

Scenario: Gross sale by network two days ago value is zero (no sales registered)

Meta:
@id_s24u55.1s5
@description gross sale by network two days ago value is zero (no sales registered)

GivenStories: precondition/sprint-24/us-55_1/aPreconditionToStoryUs55.1.story

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by network two days value is zero

Scenario: Gross sale by network eight days ago value is zero (no sales registered)

Meta:
@id_s24u55.1s6
@description gross sale by network eight days ago value is zero (no sales registered)

GivenStories: precondition/sprint-24/us-55_1/aPreconditionToStoryUs55.1.story

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale by network eight days value is zero

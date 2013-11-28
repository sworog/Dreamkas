Meta:
@sprint 24
@us 53.1

Narrative:
As a директор магазина
I want to знать сумму продаж своего магазина на этот час в сравнении с суммой продаж на этот же час вчера и неделю назад
In order to оперативно отследить провалы в продажах и успеть принять меры

Scenario: Gross Sale on hour

Meta:
@id s24u53.1s1
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture on today from data set 1

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sales subTitle
And the user checks the gross sales today value

Scenario: Gross sale on hour (no sales registered)

Meta:
@id s24u53.1s2
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sales subTitle
And the user checks the gross sales today value is zero

Scenario: Gross sale on the end of last week day

Meta:
@id s24u53.1s3
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture on last week from data set 1

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale last week value

Scenario: Gross sale on the end of last week day (no sales registered)

Meta:
@id s24u53.1s4
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale last week value is zero

Scenario: Gross sale on yesterday

Meta:
@id s24u53.1s5
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture on yesterday from data set 1

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale yesterday value

Scenario: Gross sale on yesterday (no sales registered)

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale yesterday value is zero

Scenario: Today Gross sale is more than yesterday one

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is more than yesterday one' scenario

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is more than yesterday one

Scenario: Today Gross sale is less than yesterday one

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is less than yesterday one' scenario

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is less than yesterday one

Scenario: Today Gross sale is equal yesterday one

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is equal yesterday one' scenario

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is equal yesterday one

Scenario: Today Gross sale is more than last week one

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is more than last week one' scenario

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is more than last week ago


Scenario: Today Gross sale is less than last week one

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is less than last week one' scenario

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is less than last week ago

Scenario: Today Gross sale is eqaul last week one

Meta:
@id
@description

GivenStories: precondition/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is eqaul last week one' scenario

Given the user opens the authorization page
When the user logs in using 'departmentManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is equal last week ago





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
@description checks the gross sale on current hour (title and value)
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture on today from data set 1
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sales subTitle
And the user checks the gross sales today value

Scenario: Gross sale on hour (no sales registered)

Meta:
@id s24u53.1s2
@description checks the gross sale has zero value if there is no sales registered

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sales subTitle
And the user checks the gross sales today value is zero

Scenario: Gross sale on the end of last week day

Meta:
@id s24u53.1s3
@description checks Gross sale on the end of last week day (checks the text - if the current day is correct and checks the value on the end)
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture on last week from data set 1
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale last week value on the end of the day

Scenario: Gross sale on the end of last week day (no sales registered)

Meta:
@id s24u53.1s4
@description checks gross sale on the end of last week day has zero value if there is no sales registered

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale last week value is zero

Scenario: Gross sale on yesterday end of the day

Meta:
@id s24u53.1s5
@description check the gross sale on yesterday value is correct
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture on yesterday from data set 1
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale yesterday value on the end of the day

Scenario: Gross sale on yesterday end of the day (no sales registered)

Meta:
@id s24u53.1s6
@description checks gross sale on yesterday has zero value if there is no sales registered

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale yesterday value is zero

Scenario: Today Gross sale ratio is more than yesterday one

Meta:
@id s24u53.1s7
@description checks the gross sale ratio in comprison of today value and yesterday value - today is more than yesterday
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is more than yesterday one' scenario
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross yesterday ratio text color is green
And the user checks the gross sale value is more than yesterday one

Scenario: Today Gross sale ratio is less than yesterday one

Meta:
@id s24u53.1s8
@description checks the gross sale ratio in comprison of today value and yesterday value - today is less than yesterday
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is less than yesterday one' scenario
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross yesterday ratio text color is red
And the user checks the gross sale value is less than yesterday one

Scenario: Today Gross sale ratio is equal yesterday one

Meta:
@id s24u53.1s9
@description checks the gross sale ratio in comprison of today value and yesterday value - today is equal yesterday
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is equal yesterday one' scenario
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is equal yesterday one

Scenario: Today Gross sale ratio is more than last week one

Meta:
@id s24u53.1s10
@description checks the gross sale ratio in comprison of today value and last week value - today is more than last week
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is more than last week one' scenario
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross week ratio text color is green
And the user checks the gross sale value is more than last week ago

Scenario: Today Gross sale ratio is less than last week one

Meta:
@id s24u53.1s11
@description checks the gross sale ratio in comprison of today value and last week value - today is less than last week
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is less than last week one' scenario
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross week ratio text color is red
And the user checks the gross sale value is less than last week ago

Scenario: Today Gross sale ratio is equal last week one

Meta:
@id s24u53.1s12
@description checks the gross sale ratio in comprison of today value and last week value - today is equal last week
@smoke

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user prepares fixture for 'Today Gross sale is eqaul last week one' scenario
And the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross sale value is equal last week ago

Scenario: No yesterday gross ratio is shown (no sales registered)

Meta:
@id s24u53.1s13
@description

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross yesterday sales block is not visible

Scenario: No week gross ratio is shown (no sales registered)

Meta:
@id s24u53.1s14
@description

GivenStories: precondition/sprint-24/us-53.1/aPreconditionToStoryUs53.1.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s24u531' userName and 'lighthouse' password
Then the user checks the gross week sales block is not visible





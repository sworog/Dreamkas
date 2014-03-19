Meta:
@sprint_30
@us_64
@order

Scenario: Cannot view product navigation through link by commercialManager

Meta:
@id_s30u64s3

GivenStories: precondition/sprint-30/us-64/aPreconditionToStoryUs64.story

Given there is the order in the store by 'departmentManager-s30u64'

Given the user opens last created order page
And the user logs in as 'commercialManager'

Then the user sees the 403 error

Scenario: Cannot view product navigation through link by storeManager

Meta:
@id_s30u64s4

GivenStories: precondition/sprint-30/us-64/aPreconditionToStoryUs64.story

Given there is the order in the store by 'departmentManager-s30u64'

Given the user opens last created order page
And the user logs in as 'storeManager'

Then the user sees the 403 error

Scenario: Cannot view product navigation through link by administrator

Meta:
@id_s30u64s5

GivenStories: precondition/sprint-30/us-64/aPreconditionToStoryUs64.story

Given there is the order in the store by 'departmentManager-s30u64'

Given the user opens last created order page
And the user logs in as 'watchman'

Then the user sees the 403 error

Scenario: Cannot view the orders list by commercialManager

Meta:
@id_s30u64s6

Given the user opens orders list page
And the user logs in as 'commercialManager'

Then the user sees the 403 error

Scenario: Cannot view the orders list by storeManager

Meta:
@id_s30u64s7

Given the user opens orders list page
And the user logs in as 'storeManager'

Then the user sees the 403 error

Scenario: Cannot view the orders list by administrator

Meta:
@id_s30u64s8

Given the user opens orders list page
And the user logs in as 'watchman'

Then the user sees the 403 error
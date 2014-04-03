Meta:
@sprint_30
@us_63
@order

Narrative:
Check order create field labels text

Scenario: Assert supllier select title

Meta:
@id_s30u63s8
@description assert order field label supplier

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'supplier'
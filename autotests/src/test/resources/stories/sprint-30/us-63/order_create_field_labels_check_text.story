Meta:
@sprint_30
@us_63
@order

Narrative:
Check order create field labels text

Scenario: Assert supllier select title

Meta:
@id_s30u63s5
@description assert order field label supplier

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'supplier'

Scenario: Assert name field title in adding product form

Meta:
@id_s30u63s6
@description assert order product addition form field label name

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'name' of product addition form

Scenario: Assert quantity field title in adding product form

Meta:
@id_s30u63s7
@description assert order product addition form field label quantity

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'quantity' of product addition form

Scenario: Assert retailPrice field title in adding product form

Meta:
@id_s30u63s8
@description assert order product addition form field label retailPrice

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'retailPrice' of product addition form

Scenario: Assert totalSum field title in adding product form

Meta:
@id_s30u63s9
@description assert order product addition form field label totalSum

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'totalSum' of product addition form

Scenario: Assert inventory field title in adding product form

Meta:
@id_s30u63s10
@description assert order product addition form field label inventory

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'inventory' of product addition form
Meta:
@sprint_30
@us_63
@order

Narrative:
Check order create field labels text

Scenario: Assert supllier select title

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'supplier'

Scenario: Assert name field title in adding product form

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'name' of product addition form

Scenario: Assert quantity field title in adding product form

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'quantity' of product addition form

Scenario: Assert retailPrice field title in adding product form

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'retailPrice' of product addition form

Scenario: Assert totalSum field title in adding product form

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'totalSum' of product addition form

Scenario: Assert inventory field title in adding product form

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user asserts the order field label with name 'inventory' of product addition form
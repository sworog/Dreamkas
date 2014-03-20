Meta:
@sprint_30
@us_65
@order

Narrative:
As a заведующий отделом
I want to удалить заказ, который был создан по ошибке,
In order в списке заказов были только действительные заказы

Scenario: Order deletion

Meta:
@id_s30u65s1
@smoke

GivenStories: precondition/sprint-30/us-65/aPreconditionToStoryUs65.story

Given there is the order in the store by 'departmentManager-s30u65'

Given the user opens orders list page
And the user logs in using 'departmentManager-s30u65' userName and 'lighthouse' password

When the user clicks on last created order on the orders list page

When the user clicks the order delete button and confirms the deletion

Then the user checks the orders list do not contain last created order

Scenario: Order deletion cancel - verify after cancel link click

Meta:
@id_s30u65s2

GivenStories: precondition/sprint-30/us-65/aPreconditionToStoryUs65.story

Given there is the order in the store by 'departmentManager-s30u65'

Given the user opens orders list page
And the user logs in using 'departmentManager-s30u65' userName and 'lighthouse' password

When the user clicks on last created order on the orders list page

When the user clicks the order delete button and dismisses the deletion

When the user clicks the cancel link button

Then the user checks the orders list contains entry
| number | supplier | date |
| {lastCreatedOrderNumber} | {lastSupplierOrder} | {todayDate} |

Scenario: Order deletion cancel - verify after save button click

Meta:
@id_s30u65s3

GivenStories: precondition/sprint-30/us-65/aPreconditionToStoryUs65.story

Given there is the order in the store by 'departmentManager-s30u65'

Given the user opens orders list page
And the user logs in using 'departmentManager-s30u65' userName and 'lighthouse' password

When the user clicks on last created order on the orders list page

When the user clicks the order delete button and dismisses the deletion

When the user clicks the save order button

Then the user checks the orders list contains entry
| number | supplier | date |
| {lastCreatedOrderNumber} | {lastSupplierOrder} | {todayDate} |

Scenario: Order deletion cancel - verify on the order list page

Meta:
@id_s30u65s4

GivenStories: precondition/sprint-30/us-65/aPreconditionToStoryUs65.story

Given there is the order in the store by 'departmentManager-s30u65'

Given the user opens orders list page
And the user logs in using 'departmentManager-s30u65' userName and 'lighthouse' password

When the user clicks on last created order on the orders list page

When the user clicks the order delete button and dismisses the deletion

Given the user opens orders list page

Then the user checks the orders list contains entry
| number | supplier | date |
| {lastCreatedOrderNumber} | {lastSupplierOrder} | {todayDate} |

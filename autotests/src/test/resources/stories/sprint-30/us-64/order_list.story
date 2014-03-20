Meta:
@sprint_30
@us_64
@order

Narrative:
As a заведующий отделом
I want to зафиксировать в системе заказ поставщику
In order to чтобы потом принимать поставку на основании заказа

Scenario: No orders

Meta:
@id_s30u64s1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-30/us-64/aPreconditionToStoryUs64.story

Given the user opens orders list page
And the user logs in using 'departmentManager-s30u64' userName and 'lighthouse' password

Then the user checks page contains text 'Нет невыполненных заказов'

Scenario: Order list item values checks

Meta:
@id_s30u64s2
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-30/us-64/aPreconditionToStoryUs64.story,
              precondition/sprint-30/us-64/aPreconditionToScenario2.story

Given the user opens orders list page
And the user logs in using 'departmentManager-s30u64' userName and 'lighthouse' password

Then the user checks the orders list contains exact entries
| number | supplier | date |
| {lastCreatedOrderNumber} | {lastSupplierOrder} | {todayDate} |

When the user clicks the create order link on order page menu navigation

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u64s1 |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3064 |
| quantity | 5 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3064 | шт. | 5,0 | 100,00 | 500,00 |

And the user checks the order total sum is 'Итого: 500,00 руб'

When the user clicks the save order button

Then the user checks the orders list contains exact entries
| number | supplier | date |
| 10002 | supplier-s30u64s1 | {todayDate} |
| 10001 | {lastSupplierOrder} | {todayDate} |

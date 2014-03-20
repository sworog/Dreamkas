Meta:
@sprint_30
@us_67
@order

Narrative:
Удаление продукта из заказа

Scenario: Order Product deletion

Meta:
@id_s30u67s11
@smoke

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user clicks on deletion item icon to delete edited order product

Then the user checks the last created order products list dont contains product

When the user clicks the save order button

Then the user sees error messages
|error message |
| Нужно добавить минимум один товар |

Scenario: Order Product deletion cancel

Meta:
@id_s30u67s12

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user clicks on deletion item icon to delete edited order product

Then the user checks the last created order products list dont contains product

When the user clicks the cancel link button

When the user clicks on last created order on the orders list page

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 |

Meta:
@sprint_31
@us_67
@order

Narrative:
Удаление продукта из заказа

Scenario: Order product deletion

Meta:
@id_s30u67s8
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the subCategory with name 'defaultSubCategory-s30u67' related to group named 'defaultGroup-s30u67' and category named 'defaultCategory-s30u67'
And the user sets subCategory 'defaultSubCategory-s30u67' mark up with max '10' and min '0' values

Given there is the product with 'name-3067' name, '3067' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u67', category named 'defaultCategory-s30u67', subcategory named 'defaultSubCategory-s30u67'
And there is the product with 'name-30671' name, '30671' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u67', category named 'defaultCategory-s30u67', subcategory named 'defaultSubCategory-s30u67'

Given there is the supplier with name 'SupplierOrderDeletionTest'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | SupplierOrderDeletionTest |

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-3067 |
And the user inputs quantity '5' on the order product with name 'name-3067'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order total sum is 'Итого: 500,00 руб'

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-30671 |
And the user inputs quantity '5' on the order product with name 'name-30671'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order total sum is 'Итого: 1 000,00 руб'

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3067 | шт. | 5,0 | 100,00 | 500,00 |
| name-30671 | шт. | 5,0 | 100,00 | 500,00 |
And the user checks the order total sum is 'Итого: 1 000,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

When the user clicks on the order product by name 'name-3067'
And the user clicks on delete icon and deletes order product with name 'name-3067'

Then the user checks the order products list do not contain product with name 'name-3067'
And the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-30671 | шт. | 5,0 | 100,00 | 500,00 |
And the user checks the order total sum is 'Итого: 500,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

Then the user checks the order products list do not contain product with name 'name-3067'
And the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-30671 | шт. | 5,0 | 100,00 | 500,00 |
And the user checks the order total sum is 'Итого: 500,00 руб'

Scenario: Order Product deletion cancel

Meta:
@id_s30u67s9

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user clicks on delete icon and deletes last created order product

Then the user checks the last created order products list dont contains product

When the user clicks the cancel link button

When the user clicks on last created order on the orders list page

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 |

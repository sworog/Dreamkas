Meta:
@sprint_30
@us_67
@order

Narrative:
Удаление продукта из заказа

Scenario: Order product deletion

Meta:
@id_s30u67s12
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the subCategory with name 'defaultSubCategory-s30u67' related to group named 'defaultGroup-s30u67' and category named 'defaultCategory-s30u67'
And the user sets subCategory 'defaultSubCategory-s30u67' mark up with max '10' and min '0' values

Given there is the product with 'name-3067' name, '3067' sku, '3067' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u67', category named 'defaultCategory-s30u67', subcategory named 'defaultSubCategory-s30u67'
And there is the product with 'name-30671' name, '30671' sku, '30671' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u67', category named 'defaultCategory-s30u67', subcategory named 'defaultSubCategory-s30u67'

Given there is the supplier with name 'SupplierOrderDeletionTest'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | SupplierOrderDeletionTest |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3067 |
| quantity | 5 |
And the user clicks the add order product button
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-30671 |
| quantity | 5 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3067 | шт. | 5,0 | 100,00 | 500,00 |
| name-30671 | шт. | 5,0 | 100,00 | 500,00 |
And the user checks the order total sum is 'Итого: 1 000,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

When the user clicks on the order product by name 'name-3067'
And the user clicks on deletion item icon to delete edited order product

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
@id_s30u67s13

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

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

Meta:
@sprint_31
@us_63.2
@order

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Order accept button is visible in already created order

Meta:
@id
@smoke

Given there is the user with name 'departmentManager-s31u632', position 'departmentManager-s31u632', username 'departmentManager-s31u632', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u632' managed by department manager named 'departmentManager-s31u632'

Given there is the order in the store by 'departmentManager-s31u632'

Given the user opens last created order page
And the user logs in using 'departmentManager-s31u632' userName and 'lighthouse' password

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible

Scenario: Button changes after order product addition

Meta:
@id
@smoke

Given there is the user with name 'departmentManager-s31u632', position 'departmentManager-s31u632', username 'departmentManager-s31u632', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u632' managed by department manager named 'departmentManager-s31u632'

Given there is the order in the store by 'departmentManager-s31u632'

Given there is the subCategory with name 'defaultSubCategory-s31u632' related to group named 'defaultGroup-s31u632' and category named 'defaultCategory-s31u632'
And the user sets subCategory 'defaultSubCategory-s31u632' mark up with max '10' and min '0' values

Given there is the product with 'name-30632' name, '30632' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s31u632', category named 'defaultCategory-s31u632', subcategory named 'defaultSubCategory-s31u632'

Given the user opens last created order page
And the user logs in using 'departmentManager-s31u632' userName and 'lighthouse' password

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-30632 |

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 |
| name-30632 | шт. | 1,0 | 100,00 | 100,00 |

Then the user checks the order accept button should be not visible

Then the user checks the save order button should be visible
And the user checks the cancel link button should be visible

Then the user checks the save controls text is 'Заказ изменён, подтвердите изменения, пожалуйста.'

When the user clicks the save order button

When the user clicks on last created order on the orders list page

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible

Scenario: Button changes after order product edition

Meta:
@id
@smoke

Given there is the user with name 'departmentManager-s31u632', position 'departmentManager-s31u632', username 'departmentManager-s31u632', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u632' managed by department manager named 'departmentManager-s31u632'

Given there is the order in the store by 'departmentManager-s31u632'

Given the user opens last created order page
And the user logs in using 'departmentManager-s31u632' userName and 'lighthouse' password

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible

When the user clicks on order product in last created order
And the user inputs quantity '2,0' on the order product in last created order
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| {lastCreatedProductName} | шт. | 2,0 | 100,00 | 200,00 |

Then the user checks the order accept button should be not visible

Then the user checks the save order button should be visible
And the user checks the cancel link button should be visible

Then the user checks the save controls text is 'Заказ изменён, подтвердите изменения, пожалуйста.'

When the user clicks the save order button

When the user clicks on last created order on the orders list page

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible

Scenario: Button changes after order supplier change

Meta:
@id
@smoke

Given there is the user with name 'departmentManager-s31u632', position 'departmentManager-s31u632', username 'departmentManager-s31u632', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u632' managed by department manager named 'departmentManager-s31u632'

Given there is the supplier with name 'supplier-s31u632'
And there is the order in the store by 'departmentManager-s31u632'

Given the user opens last created order page
And the user logs in using 'departmentManager-s31u632' userName and 'lighthouse' password

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s31u632 |

Then the user checks the order accept button should be not visible

Then the user checks the save order button should be visible
And the user checks the cancel link button should be visible

Then the user checks the save controls text is 'Заказ изменён, подтвердите изменения, пожалуйста.'

When the user clicks the save order button

When the user clicks on last created order on the orders list page

Then the user checks the order accept button should be visible

Then the user checks the save order button should be not visible
And the user checks the cancel link button should be not visible
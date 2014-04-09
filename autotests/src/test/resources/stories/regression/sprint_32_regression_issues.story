Meta:
@regression

Scenario: When editing order product quantity if pressing escape key button causes dynamic price sum calculation to null (0,00)

Meta:
@regression

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the product with 'name-order-regression' name, 'sku-order-regression' sku, 'barCode-order-regression' barcode

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-order-regression |
And the user presses 'ESCAPE' key button

Then the user checks the order product found by name 'name-order-regression' has sum equals to '123,00'

Scenario: When edition order product quantity with values with comma causes dynamic price sum recalculation to null (0,00)

Meta:
@regression

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the product with 'name-order-regression' name, 'sku-order-regression' sku, 'barCode-order-regression' barcode

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-order-regression |
And the user inputs quantity '10,456' on the order product with name 'name-order-regression'

Then the user checks the order product found by name 'name-order-regression' has sum equals to '1 286,09'

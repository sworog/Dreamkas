Meta:
@sprint_33
@us_69.0
@product

Narrative:
Генерация локального кода продукта

Scenario: Check there is no sku field on product create page

Meta:
@id_s33u69.0s1
@smoke

Given the user is on the product create page
And the user logs in as 'commercialManager'

Then the user checks the product sku field is not visible

Scenario: Check there is no sku field on product edit page

Meta:
@id_s33u69.0s2
@smoke

Given there is the product with 'name-s33u69.0s2' name, 'barcode-s33u69.0s2' barcode
And the user navigates to the product with name 'name-s33u69.0s2'
And the user logs in as 'commercialManager'

When the user clicks the edit button on product card view page

Then the user checks the product sku field is not visible

Scenario: Check product sku is generated correctly

Meta:
@id_s33u69.0s3
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs values in element fields
| elementName | value |
| name | name-s33u69.0s3 |
| purchasePrice | 1 |
| vat | 10 |
And the user clicks the create button

Then the user checks the products list contain product with name 'name-s33u69.0s3'
And the user checks the product with name 'name-s33u69.0s3' has sku equals to '10001'

When the user clicks on product with name 'name-s33u69.0s3'

Then the user checks the 'sku' value is '10001'

Scenario: Check product sku is generated correctly and future created product will get incremented sku number

Meta:
@id_s33u69.0s4
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given there is the product with 'name-s33u69.0s4' name, 'barcode-s33u69.0s4' barcode

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs values in element fields
| elementName | value |
| name | name-s33u69.0s5 |
| purchasePrice | 1 |
| vat | 10 |
And the user clicks the create button

Then the user checks the products list contain product with name 'name-s33u69.0s5'
And the user checks the product with name 'name-s33u69.0s5' has sku equals to '10002'

When the user clicks on product with name 'name-s33u69.0s5'

Then the user checks the 'sku' value is '10002'
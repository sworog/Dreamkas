Meta:
@us_70
@sprint_34

Narrative:
Как комерческий директор
Я хочу при добавлении алкоголя ввести все необходимые данные,
Чтобы товар можно было продавать в магазинах по всем правилам

Scenario: Product alcohol type create

Meta:
@smoke
@id_s34u70s1

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs values in element fields
| elementName | value |
| name | alcohol type product |
| vat | 0 |
| alcoholByVolume | 0 |
| volume | 1 |
And the user clicks the create button

Then the user checks the products list contain product with name 'alcohol type product'

When the user clicks on product with name 'alcohol type product'

Then the user checks the stored input values
Then the user checks the elements values
| elementName | value  |
| Алкоголь | Штучный |
| units | Литры |

Scenario: Product alcohol type edition

Meta:
@smoke
@id_s34u70s2

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs values in element fields
| elementName | value |
| name | alcohol type product edit |
| vat | 0 |
| alcoholByVolume | 0 |
| volume | 1 |
And the user clicks the create button

Then the user checks the products list contain product with name 'alcohol type product edit'

When the user clicks on product with name 'alcohol type product edit'

When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| alcoholByVolume | 1 |
| volume | 0,375 |
And the user clicks the create button
Then the user checks the stored input values

Scenario: Product alcohol type fields are not required

Meta:
@smoke
@id_s34u70s3

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs values in element fields
| elementName | value |
| name | alcohol type product 2 |
| vat | 0 |
And the user clicks the create button

Then the user checks the products list contain product with name 'alcohol type product 2'

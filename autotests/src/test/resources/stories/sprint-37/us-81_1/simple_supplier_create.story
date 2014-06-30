Meta:
@sprint_29
@sprint_37
@us_60
@us_81.1
@supplier

Narrative:
As a категорийный менеджер
I want to добавить в систему поставщика
In order to магазины сети могли начать работать с этим поставщиком

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Simple supplier create

Meta:
@id_s29u60s1
@smoke

Given the user opens the authorization page
And the user logs in as 'owner'

When the user clicks the menu suppliers item
And the user clicks the create supplier link on supplier page menu navigation
When the user inputs values on supplier page
| elementName | value |
| supplierName | OOO поставщик |
| phone | +7921321321 |
| fax | +78123255645 |
| email | mail@post.com |
| contactPerson | Валерий |
When the user clicks on the supplier create button

Then the user sees no error messages

Scenario: Cancel button cancels the creation

Meta:
@id_s29u60s2

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | Supplier |
When the user clicks on the supplier cancel button

When the user clicks the create supplier link on supplier page menu navigation
When the user inputs values on supplier page
| elementName | value |
| supplierName | Supplier |
When the user clicks on the supplier create button

Then the user sees no error messages
| error message |
| Поставщик с таким названием уже существует |

Scenario: Check supplier field title

Meta:
@id_s29u60s3

Given the user opens supplier create page
And the user logs in as 'owner'

Then the user asserts label of supplier field with name 'supplierName'
And the user asserts label of supplier field with name 'phone'
And the user asserts label of supplier field with name 'fax'
And the user asserts label of supplier field with name 'email'
And the user asserts label of supplier field with name 'contactPerson'

Scenario: Check maximum positive supplier field range

Meta:
@id_s29u60s4

Given the user runs the symfony:env:init command

Given the user opens supplier create page
And the user logs in as 'owner'

When the user generate test data with char number '100' to the supplier field name 'supplierName'
And the user generate test data with char number '300' to the supplier field name 'phone'
And the user generate test data with char number '300' to the supplier field name 'fax'
And the user generate test data with char number '300' to the supplier field name 'email'
And the user generate test data with char number '300' to the supplier field name 'contactPerson'

Then the user asserts the supplier field length with name 'supplierName' is '100'
And the user asserts the supplier field length with name 'phone' is '300'
And the user asserts the supplier field length with name 'fax' is '300'
And the user asserts the supplier field length with name 'email' is '300'
And the user asserts the supplier field length with name 'contactPerson' is '300'

When the user clicks on the supplier create button

Then the user sees no error messages

Scenario: Check maximum negative supplier field range

Meta:
@id_s29u60s5

Given the user opens supplier create page
And the user logs in as 'owner'

When the user generate test data with char number '101' to the supplier field name 'supplierName'
And the user generate test data with char number '301' to the supplier field name 'phone'
And the user generate test data with char number '301' to the supplier field name 'fax'
And the user generate test data with char number '301' to the supplier field name 'email'
And the user generate test data with char number '301' to the supplier field name 'contactPerson'

Then the user asserts the supplier field length with name 'supplierName' is '101'
And the user asserts the supplier field length with name 'phone' is '301'
And the user asserts the supplier field length with name 'fax' is '301'
And the user asserts the supplier field length with name 'email' is '301'
And the user asserts the supplier field length with name 'contactPerson' is '301'

When the user clicks on the supplier create button

Then user checks supplier form the element field 'supplierName' has error message 'Не более 100 символов'
And user checks supplier form the element field 'phone' has error message 'Не более 300 символов'
And user checks supplier form the element field 'fax' has error message 'Не более 300 символов'
And user checks supplier form the element field 'email' has error message 'Не более 300 символов'
And user checks supplier form the element field 'contactPerson' has error message 'Не более 300 символов'

Scenario: Check supplier field unique attribute

Meta:
@id_s29u60s6

Given there is the supplier with name 'supplier-test'

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-test |
When the user clicks on the supplier create button

Then user checks supplier form the element field 'supplierName' has error message 'Поставщик с таким названием уже существует'

Scenario: Check supplier field required attribute

Meta:
@id_s29u60s7

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName |  |
When the user clicks on the supplier create button

Then user checks supplier form the element field 'supplierName' has error message 'Заполните это поле'

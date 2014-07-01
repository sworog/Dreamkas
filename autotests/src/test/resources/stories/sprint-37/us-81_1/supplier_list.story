Meta:
@sprint_29
@us_61
@us_81.1
@supplier

Narrative:
As a категорийный менеджер
I want to видеть список поставщиков
In order to найти поставщика, просмотреть, отредактировать, скачать договор

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Supplier create from supplier list page

Meta:
@id_s29u61s1
@smoke

Given the user opens supplier create page
And there is the supplier with name 'supplier-s29u61s0'
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u61s1 |
When the user clicks on the supplier create button

Then the user checks the supplier list contains element with value 'supplier-s29u61s1'

Scenario: Canceling supplier creation

Meta:
@id_s29u61s2

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u61s1-1 |
When the user clicks on the supplier cancel button

Then the user checks the supplier list not contains element with value 'supplier-s29u61s1-1'

Scenario: Check the text if there is no suppliers data

Meta:
@id_s29u61s3

Given the user runs the symfony:env:init command

Given the user opens supplier list page
And the user logs in as 'owner'

Then the user checks page contains text 'Нет поставщиков'

Scenario: Supplier edit

Meta:
@id_s29u61s4
@smoke

Given there is the supplier with name 'supplier-s29s61'
And the user opens supplier list page
And the user logs in as 'owner'

When the user clicks on supplier list table element with name 'supplier-s29s61'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29s61-edited |
And the user clicks on the supplier create button

Then the user checks the supplier list contains element with value 'supplier-s29s61-edited'

Scenario: Supplier edit cancel

Meta:
@id_s29u61s5

Given there is the supplier with name 'supplier-s29s61-2'
And the user navigates to supplier page with name 'supplier-s29s61-2'
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29s61-2-edited |
And the user clicks on the supplier cancel button

Then the user checks the supplier list contains element with value 'supplier-s29s61-2'
And the user checks the supplier list not contains element with value 'supplier-s29s61-2-edited'

Scenario: Check maximum positive supplier field range in edit mode

Meta:
@id_s29u61s6

Given the user opens supplier page with random name
And the user logs in as 'owner'

When the user generate test data with char number '100' to the supplier field name 'supplierName'

Then the user asserts the supplier field length with name 'supplierName' is '100'

When the user clicks on the supplier create button

Then the user checks the supplier list contains stored element

Scenario: Check maximum negative supplier field range in edit mode

Meta:
@id_s29u61s7

Given the user opens supplier page with random name
And the user logs in as 'owner'

When the user generate test data with char number '101' to the supplier field name 'supplierName'

Then the user asserts the supplier field length with name 'supplierName' is '101'

When the user clicks on the supplier create button

Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Check supplier field unique attribute in edit mode

Meta:
@id_s29u61s8

Given there is the supplier with name 'supplier-s29s61-3'
And the user opens supplier page with random name
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29s61-3 |
When the user clicks on the supplier create button

Then the user sees error messages
| error message |
| Поставщик с таким названием уже существует |

Scenario: Check supplier field required attribute in edit mode

Meta:
@id_s29u61s9

Given the user opens supplier page with random name
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName |  |
When the user clicks on the supplier create button

Then the user sees error messages
| error message |
| Заполните это поле |
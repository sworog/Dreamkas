Meta:
@sprint_29
@us_60

Narrative:
As a категорийный менеджер
I want to добавить в систему поставщика
In order to магазины сети могли начать работать с этим поставщиком

Scenario: Simple supplier create

Meta:
@id_s29u60s1
@smoke

Given the user opens the authorization page
And the user logs in as 'commercialManager'

When the user clicks the menu suppliers item
And the user clicks the create supplier link on supplier page menu navigation
When the user inputs values on supplier page
| elementName | value |
| supplierName | OOO поставщик |
When the user clicks on the supplier create button

Then the user sees no error messages

Scenario: Cancel button cancels the creation

Meta:
@id_s29u60s2

Given the user opens supplier create page
And the user logs in as 'commercialManager'

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
And the user logs in as 'commercialManager'

Then the user asserts label of supplier field with name 'supplierName'

Scenario: Check maximum positive supplier field range

Meta:
@id_s29u60s4

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user generate test data with char number '100' to the supplier field name 'supplierName'

Then the user asserts the supplier field length with name 'supplierName' is '100'

When the user clicks on the supplier create button

Then the user sees no error messages

Scenario: Check maximum negative supplier field range

Meta:
@id_s29u60s5

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user generate test data with char number '101' to the supplier field name 'supplierName'

Then the user asserts the supplier field length with name 'supplierName' is '101'

When the user clicks on the supplier create button

Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Check supplier field unique attribute

Meta:
@id_s29u60s6

Given there is the supplier with name 'supplier-test'

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-test |
When the user clicks on the supplier create button

Then the user sees error messages
| error message |
| Поставщик с таким названием уже существует |

Scenario: Check supplier field required attribute

Meta:
@id_s29u60s7

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName |  |
When the user clicks on the supplier create button

Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: The create supplier link on supplier page menu navigation is not visible for departmentManager

Meta:
@id_s29u60s8

Given the user opens the authorization page
And the user logs in as 'departmentManager'

When the user clicks the menu suppliers item

Then the user asserts the create supplier link on supplier page menu navigation is not visible

Scenario: The create supplier link on supplier page menu navigation is not visible for administrator

Meta:
@id_s29u60s9

Given the user opens the authorization page
And the user logs in as 'watchman'

When the user clicks the menu suppliers item

Then the user asserts the create supplier link on supplier page menu navigation is not visible

Scenario: The create supplier link on supplier page menu navigation is not visible for storeManager

Meta:
@id_s29u60s10

Given the user opens the authorization page
And the user logs in as 'storeManager'

When the user clicks the menu suppliers item

Then the user asserts the create supplier link on supplier page menu navigation is not visible


Scenario: The direct supplier create url is restricted for departmentManager

Meta:
@id_s29u60s11

Given the user opens supplier create page
And the user logs in as 'departmentManager'

Then the user sees the 403 error

Scenario: The direct supplier create url is restricted for administrator

Meta:
@id_s29u60s12

Given the user opens supplier create page
And the user logs in as 'watchman'

Then the user sees the 403 error

Scenario: The direct supplier create url is restricted for storeManager

Meta:
@id_s29u60s12

Given the user opens supplier create page
And the user logs in as 'storeManager'

Then the user sees the 403 error

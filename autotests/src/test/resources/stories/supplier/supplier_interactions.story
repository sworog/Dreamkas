Meta:
@sprint_39
@us_108

Narrative:
Как владелец,
Я хочу создавать, редактировать и просматривать поставщиков торговой сети,
Чтобы контролировать закупки

Scenario: Supplier creation

Meta:
@smoke
@id_s39u108s1

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u108supplier1 |
| address | address |
| phone | phone |
| email | email |
| contactPerson | contactPerson |
And the user clicks on add button on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts the supplier list contain supplier with values
| name | address | info |
| s39u108supplier1 | address | phone, contactPerson, email |

When the user clicks on the supplier with name 's39u108supplier1'

Then the user asserts that supplier contain stored values in edit supplier modal window

Scenario: Supplier creation close icon click

Meta:
@id_s39u108s2

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u108supplier2 |
| address | address |
| phone | phone |
| email | email |
| contactPerson | contactPerson |
And the user clicks on close icon on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list not contain supplier with name 's39u108supplier2'

Scenario: Supplier edition

Meta:
@id_s39u108s3
@smoke

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplier3', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the supplier with name 's39u108supplier3'
And the user inputs values on the edit supplier modal window
| elementName | value |
| name | s39u108supplier3 edited |
| address | address edited |
| phone | phone edited |
| email | email edited |
| contactPerson | contactPerson edited |
And the user clicks on save button on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts the supplier list contain supplier with values
| name | address | info |
| s39u108supplier3 edited | address edited |  phone edited, contactPerson edited, email edited |

When the user clicks on the supplier with name 's39u108supplier3 edited'

Then the user asserts that supplier contain stored values in edit supplier modal window

Scenario: Supplier edition close icon click

Meta:
@id_s39u108s4

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplier4', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the supplier with name 's39u108supplier4'
And the user inputs values on the edit supplier modal window
| elementName | value |
| name | s39u108supplier4 edited |
| address | address edited |
| phone | phone edited |
| email | email edited |
| contactPerson | contactPerson edited |
And the user clicks on close icon on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts the supplier list contain supplier with values
| name | address | info |
| s39u108supplier4 | address | phone, contactPerson, email|

Then the user asserts that supplier list not contain supplier with name 's39u108supplier4 edited'

Scenario: Message assert if supplier list is empty

Meta:
@id_s39u108s5

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'У вас ещё нет ни одного поставщика'

Scenario: Supplier create modal window title assert

Meta:
@id_s39u108s6

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button

Then the user asserts the create new supplier modal window title is 'Добавить поставщика'

Scenario: Supplier edit modal window title assert

Meta:
@id_s39u108s7

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplier5', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the supplier with name 's39u108supplier5'

Then the user asserts the edit supplier modal window title is 'Редактирование поставщика'

Scenario: Suppliers list page title assert by click

Meta:
@id_s39u108s8
@smoke

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu suppliers item

Then the user asserts suppliers list page title is 'Поставщики'
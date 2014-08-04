Meta:
@sprint_39
@us_107

Narrative:
Как владелец,
Я хочу создавать, редактировать и просматривать магазины торговой сети,
Чтобы управлять торговой сетью

Scenario: Store creation

Meta:
@smoke
@id_s39u107s1

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user inputs values on the create new store modal window
| elementName | value |
| name | Магазин номер 1 |
| address | Контакты магазины номер 1 |
And the user clicks on add button on the create new store modal window

Then the user asserts the store list contain store with values
| name | address |
| Магазин номер 1 | Контакты магазины номер 1 |

Scenario: Store creation close icon click

Meta:
@id_s39u107s2

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user inputs values on the create new store modal window
| elementName | value |
| name | Магазин номер 2 |
| address | Контакты магазины номер 2 |
And the user clicks on the close icon on the create new store modal window

Then the user asserts the store list do not contain store with name 'Магазин номер 2'

Scenario: Store edition

Meta:
@smoke
@id_s39u107s3

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107store1' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107store1'
And the user inputs values on the edit store modal window
| elementName | value |
| name | Магазин номер 1 отредактированный |
| address | Контакты магазины номер 1 отредактированные |
And the user clicks on save button on the edit store modal window

Then the user asserts the store list contain store with values
| name | address |
| Магазин номер 1 отредактированный | Контакты магазины номер 1 отредактированные |

Scenario: Store edition close icon click

Meta:
@id_s39u107s4

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107store2' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107store2'
And the user inputs values on the edit store modal window
| elementName | value |
| name | Магазин номер 2 отредактированный |
| address | Контакты магазины номер 2 отредактированные |
And the user clicks on the close icon on the edit store modal window

Then the user asserts the store list contain store with values
| name | address |
| s39u107store2 | Address |
And the user asserts the store list do not contain store with name 'Магазин номер 2 отредактированный'

Scenario: Message assert if store list is empty

Meta:
@smoke
@id_s39u107s5

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'У вас ещё нет ни одного магазина'

Scenario: Store create modal window title assert

Meta:
@id_s39u107s6

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button

Then the user asserts the create new store modal window title is 'Добавить магазин'

Scenario: Store edit modal window title assert

Meta:
@id_s39u107s7

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107store2' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107store2'

Then the user asserts the edit store modal window title is 'Редактирование магазина'

Scenario: Stores list page title assert by click

Meta:
@smoke
@id_s39u107s8

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu stores item

Then the user asserts stores list page title is 'Магазины'
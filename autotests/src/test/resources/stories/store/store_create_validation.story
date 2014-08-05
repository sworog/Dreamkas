Meta:
@sprint_39
@us_107

Narrative:
Как владелец,
Я хочу создавать, редактировать и просматривать магазины торговой сети,
Чтобы управлять торговой сетью

Scenario: Store name field is required and cant be empty

Meta:
@id_s39u107s9

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user clicks on add button on the create new store modal window

Then the user checks the create new store modal window 'name' field has error message with text 'Заполните это поле'

Scenario: Store name field cant be duplicated

Meta:
@id_s39u107s10

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107store-val-3' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user inputs values on the create new store modal window
| elementName | value |
| name | s39u107store-val-3 |
And the user clicks on add button on the create new store modal window

Then the user checks the create new store modal window 'name' field has error message with text 'Такой магазин уже есть'

Scenario: Store address field is not required

Meta:
@id_s39u107s11

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user inputs values on the create new store modal window
| elementName | value |
| name | s39u107store-val-4 |
And the user clicks on add button on the create new store modal window

Then the user waits for modal window closing

Then the user asserts the store list contain store with values
| name | address |
| s39u107store-val-4 | |

Scenario: Store name field cantbe more than 50 symbols

Meta:
@id_s39u107s12

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
When the user generates symbols with count '51' in the create store modal window field with name 'name'
And the user clicks on add button on the create new store modal window

Then the user checks the create new store modal window 'name' field has error message with text 'Не более 50 символов'

Scenario: Store name field can 50 symbols

Meta:
@id_s39u107s13

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
When the user generates symbols with count '50' in the create store modal window field with name 'name'
And the user clicks on add button on the create new store modal window

Then the user waits for modal window closing

Then the user checks the store list contains store with stored name

Scenario: Store address field cantbe more than 100 symbols

Meta:
@id_s39u107s14

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user inputs values on the create new store modal window
| elementName | value |
| name | s39u107store-val-5 |
When the user generates symbols with count '301' in the create store modal window field with name 'address'
And the user clicks on add button on the create new store modal window

Then the user checks the create new store modal window 'address' field has error message with text 'Не более 300 символов'

Scenario: Store address field can 100 symbols

Meta:
@id_s39u107s15

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new store create button
And the user inputs values on the create new store modal window
| elementName | value |
| name | s39u107store-val-6 |
When the user generates symbols with count '300' in the create store modal window field with name 'address'
And the user clicks on add button on the create new store modal window

Then the user waits for modal window closing

Then the user asserts the store list contain store with name 's39u107store-val-6'
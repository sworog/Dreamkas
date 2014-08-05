Meta:
@sprint_39
@us_107

Narrative:
Как владелец,
Я хочу создавать, редактировать и просматривать магазины торговой сети,
Чтобы управлять торговой сетью

Scenario: Edit store name field is required and cant be empty

Meta:
@id_s39u107s16

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store1' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store1'
And the user inputs values on the edit store modal window
| elementName | value |
| name | |
And the user clicks on save button on the edit store modal window

Then the user checks the edit store modal window 'name' field has error message with text 'Заполните это поле'

Scenario: Edit store name field cant be duplicated

Meta:
@id_s39u107s17

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store2' and address 'Address'
Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store3' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store2'
And the user inputs values on the edit store modal window
| elementName | value |
| name | s39u107-edit-store3 |
And the user clicks on save button on the edit store modal window

Then the user checks the edit store modal window 'name' field has error message with text 'Такой магазин уже есть'

Scenario: Edit store address field is not required

Meta:
@id_s39u107s18

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store4' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store4'
And the user inputs values on the edit store modal window
| elementName | value |
| address | |
And the user clicks on save button on the edit store modal window

Then the user waits for modal window closing

Then the user asserts the store list contain store with values
| name | address |
| s39u107-edit-store4 | |

Scenario: Edit store name field cantbe more than 50 symbols

Meta:
@id_s39u107s19

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store5' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store5'
And the user generates symbols with count '51' in the edit store modal window field with name 'name'
And the user clicks on save button on the edit store modal window

Then the user checks the edit store modal window 'name' field has error message with text 'Не более 50 символов'

Scenario: Edit store name field can 50 symbols

Meta:
@id_s39u107s20

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store6' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store5'
And the user generates symbols with count '50' in the edit store modal window field with name 'name'
And the user clicks on save button on the edit store modal window

Then the user waits for modal window closing

Then the user checks the store list contains store with stored name

Scenario: Edit store address field cantbe more than 100 symbols

Meta:
@id_s39u107s21

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store7' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store7'
And the user generates symbols with count '301' in the edit store modal window field with name 'address'
And the user clicks on save button on the edit store modal window

Then the user checks the edit store modal window 'address' field has error message with text 'Не более 300 символов'

Scenario: Edit store address field can 100 symbols

Meta:
@id_s39u107s22

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u107@lighthouse.pro' creates the store with name 's39u107-edit-store7' and address 'Address'

Given the user opens the stores list page
And the user logs in using 's39u107@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the store with name 's39u107-edit-store7'
And the user generates symbols with count '300' in the edit store modal window field with name 'address'
And the user clicks on save button on the edit store modal window

Then the user waits for modal window closing

Then the user asserts the store list contain store with name 's39u107-edit-store7'
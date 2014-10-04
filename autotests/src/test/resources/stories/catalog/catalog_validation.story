Meta:
@sprint_38
@us_100

Narrative:
Как владелец,
Я хочу cоздавать, редактировать и удалять товарые группы в справочнике,
Чтобы упорядочить свой ассортимент в системе

Scenario: Create group validation name positive - 100 symbols

Meta:
@id_s38u100s13

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user generates symbols with count '100' in the create group modal window name field
And the user confirms OK in create new group modal window

Then the user asserts the groups list contain group with stored name

Scenario: Create group validation name negative - 101 symbols

Meta:
@id_s38u100s14

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user generates symbols with count '101' in the create group modal window name field
And the user confirms OK in create new group modal window

Then the user checks the create group modal windows name field has error message with text 'Не более 100 символов'

Scenario: Create group validation name is required

Meta:
@id_s38u100s15

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user confirms OK in create new group modal window

Then the user checks the create group modal windows name field has error message with text 'Заполните это поле'

Scenario: Create group validation name is unique

Meta:
@id_s38u100s16

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Уникальная группа'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user inputs 'Уникальная группа' in group name field in create new group modal window
And the user confirms OK in create new group modal window

Then the user checks the create group modal windows name field has error message with text 'Группа с таким названием уже существует'

Scenario: Edit group validation name positive - 100 symbols

Meta:
@id_s38u100s17

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для валидации'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для валидации'
And the user clicks on the edit group icon
And the user generates symbols with count '100' in the edit group modal window name field
And the user confirms OK in edit group modal window

Then the user asserts the group title equals stored name

Scenario: Edit group validation name negative - 101 symbols

Meta:
@id_s38u100s18

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для валидации4'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для валидации4'
And the user clicks on the edit group icon
And the user generates symbols with count '101' in the edit group modal window name field
And the user confirms OK in edit group modal window

Then the user checks the edit group modal windows name field has error message with text 'Не более 100 символов'

Scenario: Edit group validation name is required

Meta:
@id_s38u100s19

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для валидации3'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для валидации3'
And the user clicks on the edit group icon
And the user inputs '' in group name field in edit group modal window
And the user confirms OK in edit group modal window

Then the user checks the edit group modal windows name field has error message with text 'Заполните это поле'

Scenario: Edit group validation name is unique

Meta:
@id_s38u100s20

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для валидации1'
And the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для валидации2'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для валидации2'
And the user clicks on the edit group icon
And the user inputs 'Группа для валидации1' in group name field in edit group modal window
And the user confirms OK in edit group modal window

Then the user checks the edit group modal windows name field has error message with text 'Группа с таким названием уже существует'
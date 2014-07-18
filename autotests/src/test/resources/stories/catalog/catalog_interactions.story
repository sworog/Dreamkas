Meta:
@sprint_38
@us_100

Narrative:
Как владелец,
Я хочу cоздавать, редактировать и удалять товарые группы в справочнике,
Чтобы упорядочить свой ассортимент в системе

Scenario: Group creation confirmation ok

Meta:
@smoke
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user inputs 'Новая группа' in group name field in create new group modal window
And the user confirms OK in create new group modal window

Then the user asserts the groups list contain group with name 'Новая группа'

Scenario: Group creation confirmation cancel

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user inputs 'Новая группа1' in group name field in create new group modal window
And the user confirms Cancel in create new group modal window

Then the user asserts the groups list not contain group with name 'Новая группа1'

Scenario: Group edition confirmation ok

Meta:
@smoke
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа до редактирования'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа до редактирования'
And the user clicks on the edit group icon
And the user inputs 'Группа после редактирования' in group name field in edit group modal window
And the user confirms OK in edit group modal window

Then the user asserts the groups list contain group with name 'Группа после редактирования'
Then the user asserts the groups list not contain group with name 'Группа до редактирования'

Scenario: Group edition confirmation cancel

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа до редактирования1'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа до редактирования1'
And the user clicks on the edit group icon
And the user inputs 'Группа после редактирования1' in group name field in edit group modal window
And the user confirms Cancel in edit group modal window

Then the user asserts the groups list contain group with name 'Группа до редактирования1'
Then the user asserts the groups list not contain group with name 'Группа после редактирования1'

Scenario: Group deletion confirmation ok

Meta:
@smoke
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для удаления'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для удаления'
And the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window
And the user clicks on delete group confirm button in edit group modal window

Then the user asserts the groups list not contain group with name 'Группа для удаления'

Scenario: Group deletion confirmation cancel

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для удаления1'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для удаления1'
And the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window
And the user clicks on delete group confirm button in edit group modal window

Then the user asserts the groups list contain group with name 'Группа для удаления1'

Scenario: Catalog menu navigation bar link navigation assert to proper page

Meta:
@smoke
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu catalog item

Then the user asserts catalog title is 'Ассортимент'

Scenario: Catalog title assert

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

Then the user asserts catalog title is 'Ассортимент'

Scenario: Choosen group title assert

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для выбора'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для выбора'

Then the user asserts choosen group title is 'Группа для выбора'

Scenario: Create group modal window title assert

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page

Then the user asserts the create group modal window title is 'Добавить группу'

Scenario: Edit group modal window title assert

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Тест группа'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Тест группа'
And the user clicks on the edit group icon

Then the user asserts the edit group modal window title is 'Редактирование группы'

Scenario: No groups message assert

Meta:
@id_

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'У вас пока нет ни групп, ни товаров!'

Scenario: When adding new group and navigation to it

Meta:
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user inputs 'New group' in group name field in create new group modal window
And the user confirms OK in create new group modal window

Then the user asserts choosen group title is 'New group'

Scenario: Deleting the group with name, which had the already deleted group

Meta:
@smoke
@id_

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'GroupDeletion'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'GroupDeletion'
And the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window
And the user clicks on delete group confirm button in edit group modal window

Then the user asserts the groups list not contain group with name 'GroupDeletion'

When the user clicks on the add new group button on the catalog page
And the user inputs 'GroupDeletion' in group name field in create new group modal window
And the user confirms OK in create new group modal window

Then the user asserts the groups list contain group with name 'GroupDeletion'




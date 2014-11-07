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
@id_s38u100s1

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user inputs 'Новая группа' in group name field in create new group modal window
And the user confirms OK in create new group modal window

Then the user waits for modal window closing
And the user asserts the groups list contain group with name 'Новая группа'

Scenario: Group creation confirmation cancel

Meta:
@id_s38u100s2

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page
And the user inputs 'Новая группа1' in group name field in create new group modal window
And the user clicks on close icon in create new group modal window

Then the user asserts catalog title is 'Ассортимент'
And the user asserts the groups list not contain group with name 'Новая группа1'

Scenario: Group edition confirmation ok

Meta:
@smoke
@id_s38u100s3

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа до редактирования'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа до редактирования'
And the user clicks on the edit group icon
And the user inputs 'Группа после редактирования' in group name field in edit group modal window
And the user confirms OK in edit group modal window

Then the user waits for modal window closing
And the user asserts group title is 'Группа после редактирования'

When the user clicks on the back link long arrow icon on the group page

Then the user asserts the groups list contain group with name 'Группа после редактирования'
Then the user asserts the groups list not contain group with name 'Группа до редактирования'

Scenario: Group edition confirmation cancel

Meta:
@id_s38u100s4

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа до редактирования1'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа до редактирования1'
And the user clicks on the edit group icon
And the user inputs 'Группа после редактирования1' in group name field in edit group modal window
And the user clicks on close icon in edit group modal window

Then the user asserts group title is 'Группа до редактирования1'

When the user clicks on the back link long arrow icon on the group page

Then the user asserts the groups list contain group with name 'Группа до редактирования1'
Then the user asserts the groups list not contain group with name 'Группа после редактирования1'

Scenario: Group deletion confirmation ok

Meta:
@smoke
@id_s38u100s5

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для удаления'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для удаления'
And the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window
And the user clicks on delete group confirm button in edit group modal window
When пользователь* находится в модальном окне 'редактирования группы'
And пользователь* в модальном окне нажимает кнопку продолжить

Then the user waits for modal window closing
And the user waits for page finishing loading

Then the user asserts catalog title is 'Ассортимент'
And the user asserts the groups list not contain group with name 'Группа для удаления'

Scenario: Catalog menu navigation bar link navigation assert to proper page

Meta:
@smoke
@id_s38u100s6

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu catalog item

Then the user asserts catalog title is 'Ассортимент'

Scenario: Catalog title assert

Meta:
@id_s38u100s7

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

Then the user asserts catalog title is 'Ассортимент'

Scenario: Choosen group title assert

Meta:
@id_s38u100s8

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Группа для выбора'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Группа для выбора'

Then the user asserts group title is 'Группа для выбора'

Scenario: Create group modal window title assert

Meta:
@id_s38u100s9

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new group button on the catalog page

Then the user asserts the create group modal window title is 'Добавить группу'

Scenario: Edit group modal window title assert

Meta:
@id_s38u100s10

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'Тест группа'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'Тест группа'
And the user clicks on the edit group icon

Then the user asserts the edit group modal window title is 'Редактирование группы'

Scenario: No groups message assert

Meta:
@id_s38u100s11

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'У вас пока нет ни одной группы товаров.'

Scenario: Deleting the group with name, which had the already deleted group

Meta:
@smoke
@id_s38u100s12
@us_119.4

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user with email 's28u100@lighthouse.pro' creates group with name 'GroupDeletion'

Given the user opens catalog page
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the group with name 'GroupDeletion'
And the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window
And the user clicks on delete group confirm button in edit group modal window

When пользователь* находится в модальном окне 'редактирования группы'
Then пользователь* в модальном окне проверяет, что поле с именем 'заголовок успешного удаления' имеет значение 'Группа удалена'
And пользователь* в модальном окне проверяет, что поле с именем 'название удаленной группы' имеет значение 'GroupDeletion'

When пользователь* в модальном окне нажимает кнопку продолжить

Then the user waits for modal window closing
And the user waits for page finishing loading

Then the user asserts the groups list not contain group with name 'GroupDeletion'

When the user clicks on the add new group button on the catalog page
And the user inputs 'GroupDeletion' in group name field in create new group modal window
And the user confirms OK in create new group modal window

Then the user waits for modal window closing
And the user asserts the groups list contain group with name 'GroupDeletion'
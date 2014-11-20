Meta:
@sprint_38
@us_101

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять товары,
Чтобы отслеживать их движение и продавать, используя возможности LH

Scenario: The group autocomplete is automatically set to product current group if the user creates new product

Meta:
@id_s38u101s40

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup'
And the user navigates to the group with name 's38u101autoGroup'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on create product button on group page

Then the user asserts the group field value is 's38u101autoGroup'

Scenario: The product is created in parent group

Meta:
@id_s38u101s41
@smoke

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup'
And the user navigates to the group with name 's38u101autoGroup'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101autocompleteName1 |
And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101autocompleteName1'
And the user asserts group title is 's38u101autoGroup'
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

Scenario: The already created group can be choosen from autocomplete result

Meta:
@id_s38u101s42
@smoke

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup'
Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup1'
And the user navigates to the group with name 's38u101autoGroup'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| group | s38u101autoGroup1 |
| name | s38u101autocompleteName2 |
And the user confirms OK in create new product modal window

Then the user waits for modal window closing
And the user waits for page finishing loading

Then the user asserts the groups list contain product with name 's38u101autocompleteName2'
And the user asserts group title is 's38u101autoGroup1'

Scenario: Create the product with new group

Meta:
@id_s38u101s43
@smoke

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup'
And the user navigates to the group with name 's38u101autoGroup'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| group | #s38u101autoGroup2 |
| name | s38u101autocompleteName3 |
And the user confirms OK in create new product modal window

Then the user waits for modal window closing
And the user waits for page finishing loading

Then the user asserts the groups list contain product with name 's38u101autocompleteName3'
And the user asserts group title is 's38u101autoGroup2'

Scenario: Create already existing group during product creation

Meta:
@id_s38u101s44

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup'
Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup3'
And the user navigates to the group with name 's38u101autoGroup'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| group | #s38u101autoGroup3 |
| name | s38u101autocompleteName4 |
And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'group' field has error message with text 'Группа с таким названием уже существует'

Scenario: Product moving to another group

Meta:
@id_s38u101s45
@smoke

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup'
Given the user with email 's28u101@lighthouse.pro' creates group with name 's38u101autoGroup4'
And the user navigates to the group with name 's38u101autoGroup'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101autocompleteName5', units 'шт.', barcode 'autocompleteBar5', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 's38u101autoGroup'

And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101autocompleteName5'
And the user inputs values in edit product modal window
| elementName | value |
| group | s38u101autoGroup4 |
And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101autocompleteName5'
And the user asserts group title is 's38u101autoGroup4'

Scenario: Create new group with 100 symbols name from product group autocomplete control

Meta:
@skip
@ignore

!--not implemented()

Scenario: Create new group with 101 symbols name from product group autocomplete control

Meta:
@skip
@ignore

!--not implemented()
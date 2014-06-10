Meta:
@sprint_36
@us_77

Narrative:
Как владелец торговой точки,
Я хочу изменить данные своей учетной записи,
Чтобы держать доступ к аккаунту под контролем

Scenario: Account edition - email and name

Meta:
@smoke
@id_s36u77s1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-36/us-77/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 'test.user@lighthouse.pro' userName and 'lighthouse' password

When the user opens menu navigation bar user card
And the user clicks the edit button on the user card page

When the user inputs values on user edit page
| elementName | value |
| name | Алла Павловна |
| email | alla.pavlovna@lighthouse.pro |
And the user inputs password 'lighthouse' on the login page

When the user clicks on save user data button click

Then the user checks stored values on user card page

Scenario: Authorization after password account edition

Meta:
@smoke
@id_s36u77s2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-36/us-77/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 'test.user@lighthouse.pro' userName and 'lighthouse' password

When the user opens menu navigation bar user card
And the user clicks the edit button on the user card page

When the user inputs values on user edit page
| elementName | value |
| password | 12345678 |

When the user clicks on save user data button click
And the user logs out

When the user inputs values on login page
| elementName | value |
| userName | test.user@lighthouse.pro |
| password | lighthouse |
And the user clicks on sign in button and logs in

Then the user sees exact error messages
| error message |
| Неверный логин или пароль |

When the user inputs values on login page
| elementName | value |
| userName | test.user@lighthouse.pro |
| password | 12345678 |
And the user clicks on sign in button and logs in

Then the user checks that authorized is 'test.user@lighthouse.pro' user

Scenario: Account link is name

Meta:
@id_s36u77s3

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-36/us-77/aPreconditionToUserCreation.story

Given the user opens user edit page
And the user logs in using 'test.user@lighthouse.pro' userName and 'lighthouse' password

When the user inputs values on user edit page
| elementName | value |
| name | Алла Павловна |
And the user inputs password 'lighthouse' on the login page

When the user clicks on save user data button click

Then the user checks stored values on user card page

Then the user checks that authorized is 'Алла Павловна' user

Scenario: Account link is email

Meta:
@id_s36u77s4

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-36/us-77/aPreconditionToUserCreation.story

Given the user opens user edit page
And the user logs in using 'test.user@lighthouse.pro' userName and 'lighthouse' password

When the user inputs values on user edit page
| elementName | value |
| email | alla.pavlovna@lighthouse.pro |
And the user inputs value '' in the field with elementName 'name'
And the user inputs password 'lighthouse' on the login page

When the user clicks on save user data button click

Then the user checks stored values on user card page

Then the user checks that authorized is 'alla.pavlovna@lighthouse.pro' user

Scenario: Authorization after email account edition

Meta:
@smoke
@id_s36u77s5

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-36/us-77/aPreconditionToUserCreation.story

Given the user opens user edit page
And the user logs in using 'test.user@lighthouse.pro' userName and 'lighthouse' password

When the user inputs values on user edit page
| elementName | value |
| email | alla.pavlovna@lighthouse.pro |
And the user inputs value '' in the field with elementName 'name'
And the user inputs password 'lighthouse' on the login page

When the user clicks on save user data button click

Then the user checks stored values on user card page

Then the user checks that authorized is 'alla.pavlovna@lighthouse.pro' user

When the user logs out

When the user inputs values on login page
| elementName | value |
| userName | alla.pavlovna@lighthouse.pro |
| password | lighthouse |
And the user clicks on sign in button and logs in

Then the user checks that authorized is 'alla.pavlovna@lighthouse.pro' user
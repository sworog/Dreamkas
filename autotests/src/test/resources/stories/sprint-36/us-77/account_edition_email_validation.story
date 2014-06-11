Meta:
@sprint_36
@us_77

Narrative:
Как владелец торговой точки,
Я хочу изменить данные своей учетной записи,
Чтобы держать доступ к аккаунту под контролем

Scenario: Email field validation negative

Meta:
@id_s36u77s15

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs value in the field with elementName 'email'
And the user inputs password 'lighthouse' on the user edit page
And the user clicks on save user data button

Then the user user sees errorMessage

Examples:
examplesTable/email/negative.table

Scenario: Email field validation positive

Meta:
@id_s36u77s16

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs value in the field with elementName 'email'
And the user inputs password 'lighthouse' on the user edit page
And the user clicks on save user data button

Then the user asserts the element 'email' value is equal to value on user card page

Examples:
examplesTable/email/positive.table
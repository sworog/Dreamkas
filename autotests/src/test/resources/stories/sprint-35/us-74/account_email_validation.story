Meta:
@sprint_35
@us_74

Narrative:
Как владелец торговой сети,
Я хочу зарегестрировать аккаун в LH,
Чтобы иметь в систему свою учетную запись

Scenario: Email is unique

Meta:
@id_s35u74s11

Given the user opens lighthouse sign up page

When the user inputs 'test1@lighthouse.pro' value in email field
And the user clicks on sign up button

Then the user checks the sign up text is expected

Given the user opens lighthouse sign up page

When the user inputs 'test1@lighthouse.pro' value in email field
And the user clicks on sign up button

Then the user sees exact error messages
| error message |
| Пользователь с таким email уже существует |

Scenario: Email field validation negative

Meta:
@id_s35u74s12

Given the user opens lighthouse sign up page

When the user inputs value in email field
And the user clicks on sign up button

Then the user user sees errorMessage

Examples:
examplesTable/email/negative.table

Scenario: Email field validation positive

Meta:
@id_s35u74s13

Given the user opens lighthouse sign up page

When the user inputs value in email field
And the user clicks on sign up button

Then the user checks the sign up text is expected
And the user asserts the element 'email' value is equal to value

Examples:
examplesTable/email/positive.table

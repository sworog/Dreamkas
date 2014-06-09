Meta:
@sprint_36
@us_76

Narrative:
Как владелец торговой точки,
Я хочу восстановить забытый пароль к моей учетной записи,
Чтобы продолжить пользоваться системой

Scenario: Password recovery

Meta:
@id_s36u76s1
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-35/us-74/aPreconditionForAccountRegistrationScenario.story

Given the user opens lighthouse sign up page

When the user inputs 'lighthouse.eddystone@gmail.com' value in email field
And the user clicks on sign up button

When the user gets the last email message from the test email inbox folder

Given the user prepares email inbox

When the user clicks on forgot password link
And the user inputs 'lighthouse.eddystone@gmail.com' value in restore password email field

When the user clicks on restore password button

Then the user checks the restore password text is 'Ваш новый пароль отправлен вам на email.'
And the user asserts the elements have values on auth page
| elementName | value |
| email | lighthouse.eddystone@gmail.com |

When the user gets the last email message from the test email inbox folder

Then the user assert the email message from value is 'lighthouse.eddystone@gmail.com'
And the user assert the email message subject value is 'Восстановление пароля в Lighthouse'
And the user assert the restore password email message content matches the required template

When the user inputs stored password from restore password email in password field
And the user clicks on sign in button and logs in

Then the user checks that authorized is 'lighthouse.eddystone@gmail.com' user

When the user logs out

Scenario: Password recovery empty field validation

Meta:
@id_s36u76s2

Given the user opens lighthouse restore password page

When the user inputs '' value in restore password email field
And the user clicks on restore password button

Then the user sees exact error messages
| error message |
| Заполните это поле |

Scenario: Password recovery no such email validation

Meta:
@id_s36u76s3

Given the user opens lighthouse restore password page

When the user inputs 'lighthouse@lighthouse.lighthouse' value in restore password email field
And the user clicks on restore password button

Then the user sees exact error messages
| error message |
| Пользователь с таким e-mail не зарегистрирован в системе |

Scenario: Password recovery title checks

Meta:
@id_s36u76s4

Given the user opens lighthouse restore password page

Then the user asserts the restore password page title text is 'Восстановление пароля'

Then the user asserts the restore password page text is 'Для восстановления пароля укажите ваш email.'
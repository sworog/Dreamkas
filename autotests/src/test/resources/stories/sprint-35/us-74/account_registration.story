Meta:
@sprint_35
@us_74

Narrative:
Как владелец торговой сети,
Я хочу зарегестрировать аккаун в LH,
Чтобы иметь в систему свою учетную запись

Scenario: Account registration

Meta:
@smoke
@id_s35u74s1

Given the user runs the symfony:env:init command

Given the user opens lighthouse sign up page

When the user inputs 'lighthouse.eddystone@gmail.com' value in email field

When the user clicks on sign up button

Then the user checks the sign up text is 'Ваша учетная запись успешно создана. Для входа введите пароль присланный вам на емаил'

And the user asserts the elements have values on auth page
| elementName | value |
| email | lighthouse.eddystone@gmail.com |

When the user gets the last email message from the test email inbox folder

Then the user assert the email message from value is 'lighthouse test@dfdfdf.com'
And the user assert the email message subject value is 'Регистрация в LH'
And the user assert the email message content value contains 'Рады вас поздравить..'
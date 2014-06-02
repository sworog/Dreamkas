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

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-35/us-74/aPreconditionForAccountRegistrationScenario.story

Given the user opens lighthouse sign up page

When the user inputs 'lighthouse.eddystone@gmail.com' value in email field
And the user clicks on sign up button

Then the user checks the sign up text is expected
And the user asserts the elements have values on auth page
| elementName | value |
| email | lighthouse.eddystone@gmail.com |

When the user gets the last email message from the test email inbox folder

Then the user assert the email message from value is 'lighthouse.eddystone@gmail.com'
And the user assert the email message subject value is 'Добро пожаловать в Lighthouse'
And the user assert the email message content matches the required template
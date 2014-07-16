Meta:
@sprint_35
@us_75.1

Narrative:
Как владелец сети,
Я хочу авторизироваться,
Чтобы управлять сетью

Scenario: Account succesful authorization

Meta:
@smoke
@s35u75.1s1

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
And the user inputs stored password from email in password field
And the user clicks on sign in button and logs in

Then the user checks that authorized is 'lighthouse.eddystone@gmail.com' user
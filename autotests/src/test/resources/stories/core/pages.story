Meta:
@core

Scenario: Not found page

Meta:
@regression
@sprint_41
@tech_41

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given the user opens the authorization page
And пользователь авторизуется в системе используя адрес электронной почты 's28u100@lighthouse.pro' и пароль 'lighthouse'

Given user opens url '/xxx'
Then user checks h1 text is 'Такой страницы не существует #404'


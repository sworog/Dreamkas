Meta:
@core

Scenario: Not found page

Meta:
@regression
@sprint_41
@tech_41

GivenStories: precondition/sprint-38/us-100/aPreconditionToUserCreation.story

Given user opens url '/'
And the user logs in using 's28u100@lighthouse.pro' userName and 'lighthouse' password

Given user opens url '/xxx'
Then user checks h1 text is 'Такой страницы не существует #404'


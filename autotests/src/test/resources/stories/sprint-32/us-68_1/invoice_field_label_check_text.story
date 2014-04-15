Meta:
@sprint_32
@us_68.1
@invoice

Narrative: Assert field labels

Scenario: Assert acceptanceDate date input field title

Meta:
@id
@description assert acceptanceDate field label supplier

GivenStories: precondition/sprint-32/us-68_1/aPreconditionToStoryUs68.1.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

Then the user asserts the invoice field label with name 'acceptanceDate'

Scenario: Assert supplier select field title

Meta:
@id
@description assert supplier field label supplier

GivenStories: precondition/sprint-32/us-68_1/aPreconditionToStoryUs68.1.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

Then the user asserts the invoice field label with name 'supplier'

Scenario: Assert accepter input field title

Meta:
@id
@description assert accepter field label supplier

GivenStories: precondition/sprint-32/us-68_1/aPreconditionToStoryUs68.1.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

Then the user asserts the invoice field label with name 'accepter'

Scenario: Assert supplierInvoiceNumber input field title

Meta:
@id
@description assert supplierInvoiceNumber field label supplier

GivenStories: precondition/sprint-32/us-68_1/aPreconditionToStoryUs68.1.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

Then the user asserts the invoice field label with name 'supplierInvoiceNumber'

Scenario: Assert legalEntity input field title

Meta:
@id
@description assert legalEntity field label supplier

GivenStories: precondition/sprint-32/us-68_1/aPreconditionToStoryUs68.1.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

Then the user asserts the invoice field label with name 'legalEntity'

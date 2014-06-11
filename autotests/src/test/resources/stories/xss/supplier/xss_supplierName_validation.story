Meta:
@sprint_29
@xss
@supplier

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: XSS supplier name validation

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs value in supplierName field on supplier page
And the user clicks on the supplier create button

Then the user checks the supplier list contains element with value

Examples:
examplesTable/xss/xss.table

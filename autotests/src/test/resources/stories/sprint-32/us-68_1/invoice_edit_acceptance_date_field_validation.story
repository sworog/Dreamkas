Meta:
@sprint_32
@us_68.1
@invoice

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Invoice acceptanceDate is required

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | ! |

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate validation good manual

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | !03.12.2012 10:45 |

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation manual negative numbers

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | !123454567890 |

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation manual negative

Meta:
@id

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs value in the 'acceptanceDate' invoice field

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Заполните это поле |

Examples:
| value |
| !12345456789 |
| !aaasdfsfsfsf |
| !AAASDFSFSFSS |
| !русскийнабор |
| !РУССКИЙНАБОР |
| !Dfdf dfdf dfd |
| !"№;%:?*()_+ |
| !"56gfЛВ |

Scenario: Invoice acceptanceDate validation through datepicker good

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | todayDateAndTime |

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation through datepicker negative1

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 27.03.2013 9999:9999 |

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation through datepicker negative2

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 27.03.2013 1155:222255 |

When the user accepts products and saves the invoice

Then the user sees no error messages
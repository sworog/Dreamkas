Meta:
@sprint_32
@us_68.1
@invoice

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Invoice product edition with Tab between fields

Meta:
@id_
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |

When the user inputs '5' into active element, which has focus on the page

And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs '110' into active element, which has focus on the page

And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,0 | 110,00 | 550,00 | 56,78 |

Scenario: After adding the invoice product the focus should be on autocomplete field

Meta:
@id_
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |

And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,0 | 110,00 | 550,00 | 56,78 |

Then the user asserts the autocomplete invoice field has focus

Scenario: Ending the invoice product edition by ENTER key press

Meta:
@id_
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-32681 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Ending the invoice product edition by focus change

Meta:
@id_
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |

When the user focuses out on the invoice page

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-32681 | шт. | 1,0 | 100,00 | 100,00 |
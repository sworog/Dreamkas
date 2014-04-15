Meta:
@sprint_32
@us_68.1
@invoice

Scenario: Invoice acceptanceDate is required

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | ! |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Заполните поле |
| Нужно добавить минимум один товар |

Scenario: Invoice acceptance date is prefilled on the first invoice create

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

Then the user checks the acceptanceDate field is prefilled by nowDate

Scenario: Invoice acceptanceDate validation good manual

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | !03.12.2012 10:45 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-31681 |
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation manual negative numbers

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | !123454567890 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-31681 |
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation manual negative

Meta:
@id

Given there is the user with name 'departmentManager-s31u681', position 'departmentManager-s31u681', username 'departmentManager-s31u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u681' managed by department manager named 'departmentManager-s31u681'

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs value in the 'acceptanceDate' invoice field

Then the user sees exact error messages
| error message |
| Заполните это поле |
| Нужно добавить минимум один товар |

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

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-31681 |
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation through datepicker negative1

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | 27.03.2013 9999:9999 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-31681 |
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice acceptanceDate validation through datepicker negative2

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | 27.03.2013 1155:222255 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-31681 |
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees no error messages
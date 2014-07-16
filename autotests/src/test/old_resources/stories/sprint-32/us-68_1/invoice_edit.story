Meta:
@sprint_32
@us_68.1
@invoice

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Invoice full data editition

Meta:
@id_
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'
And there is the product with 'name-s32u681s1' name, 'barCode-s32u681s1' barcode

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

Then the user checks the invoice total sum is 'Итого: 100,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user clicks on invoice product in last created invoice
And the user inputs quantity '2,0' on the invoice product in last created invoice
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '110' on the invoice product in last created invoice
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| {lastCreatedProductName} | шт. | 2,0 | 110,00 | 220,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 220,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-s32u681s1 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '10' on the invoice product with name 'name-s32u681s1'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '110' on the invoice product with name 'name-s32u681s1'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice total sum is 'Итого: 1 320,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| {lastCreatedProductName} | шт. | 2,0 | 110,00 | 220,00 руб. | 0,00 руб. |
| name-s32u681s1 | шт. | 10,0 | 110,00 | 1 100,00 руб. | 0,00 руб. |

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter edited |
| supplierInvoiceNumber | supplierInvoiceNumber-1 edited |
| legalEntity | legalEntity edited |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks stored values on invoice page

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| {lastCreatedProductName} | шт. | 2,0 | 110,00 | 220,00 руб. | 0,00 руб. |
| name-s32u681s1 | шт. | 10,0 | 110,00 | 1 100,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 1 320,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

Scenario: Invoice supplier edit - check successful edition

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks stored values on invoice page

Scenario: Invoice supplier edit - check via cancel

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |

When the user clicks the invoice cancel link button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks values on the invoice page
| elementName | value |
| supplier | {lastCreatedSupplierName} |

Scenario: Invoice acceptanceDate edit - check successful edition

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks stored values on invoice page

Scenario: Invoice acceptanceDate edit - check via cancel

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |

When the user clicks the invoice cancel link button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks values on the invoice page
| elementName | value |
| acceptanceDate | {lastCreatedInvoiceAcceptanceDateValue} |

Scenario: Invoice accepter edit - check successful edition

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| accepter | accepter edited |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks stored values on invoice page

Scenario: Invoice accepter edit - check via cancel

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| accepter | accepter edited |

When the user clicks the invoice cancel link button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks values on the invoice page
| elementName | value |
| accepter | {lastCreatedInvoiceAccepterValue} |

Scenario: Invoice legalEntity edit - check successful edition

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| legalEntity | legalEntity edited |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks stored values on invoice page

Scenario: Invoice legalEntity edit - check via cancel

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| legalEntity | legalEntity edited |

When the user clicks the invoice cancel link button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks values on the invoice page
| elementName | value |
| legalEntity | {lastCreatedInvoiceLegalEntityValue} |

Scenario: Invoice supplierInvoiceNumber edit - check successful edition

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplierInvoiceNumber | supplierInvoiceNumber edited |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks stored values on invoice page

Scenario: Invoice supplierInvoiceNumber edit - check via cancel

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplierInvoiceNumber | supplierInvoiceNumber edited |

When the user clicks the invoice cancel link button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks values on the invoice page
| elementName | value |
| supplierInvoiceNumber | {lastCreatedInvoiceSupplierInvoiceNumberValue} |

Scenario: Invoice product edit - add new product

Meta:
@id_
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'
And there is the product with 'name-s32u681s1' name, 'barCode-s32u681s1' barcode

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-s32u681s1 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '10' on the invoice product with name 'name-s32u681s1'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 руб. | 0,00 руб. |
| name-s32u681s1 | шт. | 10,0 | 123,00 | 1 230,00 руб. | 0,00 руб. |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by last created invoice number
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with last created invoice number

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 руб. | 0,00 руб. |
| name-s32u681s1 | шт. | 10,0 | 123,00 | 1 230,00 руб. | 0,00 руб. |
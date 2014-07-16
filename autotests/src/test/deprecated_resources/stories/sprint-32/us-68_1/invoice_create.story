Meta:
@sprint_32
@us_68.1
@invoice

Scenario: Invoice create

Meta:
@id_
@invoice
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks the menu invoices item
And the user clicks the create invoice link on invoice page menu navigation

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '5' on the invoice product with name 'name-32681'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '110' on the invoice product with name 'name-32681'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,0 | 110,00 | 550,00 руб. | 0,00 руб. |

And the user checks the invoice total sum is 'Итого: 550,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user checks stored values on invoice page

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,0 | 110,00 | 550,00 руб. | 0,00 руб. |

Scenario: Invoice number check

Meta:
@id_
@invoice
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks the menu invoices item
And the user clicks the create invoice link on invoice page menu navigation

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '5' on the invoice product with name 'name-32681'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user asserts the invoice number is '10001'

Scenario: Invoice create with three products

Meta:
@id_
@invoice
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToInvoiceCreateStory.story

Given there is the product with 'name-3268101' name, '3268101' barcode, 'unit' type, '78.90' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'
And there is the product with 'name-3268102' name, '3268102' barcode, 'weight' type, '56.78' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given the user opens the authorization page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks the menu invoices item
And the user clicks the create invoice link on invoice page menu navigation

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '5,7' on the invoice product with name 'name-32681'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '110' on the invoice product with name 'name-32681'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,7 | 110,00 | 627,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 627,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-3268101 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '5,67' on the invoice product with name 'name-3268101'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '123,67' on the invoice product with name 'name-3268101'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,7 | 110,00 | 627,00 руб. | 0,00 руб. |
| name-3268101 | шт. | 5,67 | 123,67 | 701,21 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 1 328,21 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-3268102 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '45,789' on the invoice product with name 'name-3268102'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,7 | 110,00 | 627,00 руб. | 0,00 руб. |
| name-3268101 | шт. | 5,67 | 123,67 | 701,21 руб. | 0,00 руб. |
| name-3268102 | кг | 45,789 | 56,78 | 2 599,90 руб. | 0,00 руб. |

And the user checks the invoice total sum is 'Итого: 3 928,11 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user checks stored values on invoice page

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,7 | 110,00 | 627,00 руб. | 0,00 руб. |
| name-3268101 | шт. | 5,67 | 123,67 | 701,21 руб. | 0,00 руб. |
| name-3268102 | кг | 45,789 | 56,78 | 2 599,90 руб. | 0,00 руб. |

And the user checks the invoice total sum is 'Итого: 3 928,11 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

Scenario: Verify autocomplete invoice product with no price is choosen

Meta:
@id_

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'
Given there is the supplier with name 'supplier-s32u681s1'
And there is the subCategory with name 'defaultSubCategory-s32u6811' related to group named 'defaultGroup-s32u6811' and category named 'defaultCategory-s32u6811'
Given there is the product with 'name-326811' name, '326811' barcode, 'unit' type, '' purchasePrice of group named 'defaultGroup-s32u6811', category named 'defaultCategory-s32u6811', subcategory named 'defaultSubCategory-s32u6811'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !name-326811 |
Then the user waits for the invoice product edition preloader finish

Then the user checks the autocomplete result list contains exact entries
| result |
| Нет результатов |

Scenario: Verify autocomplete invoice product with price with no mark up is choosen

Meta:
@id_

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'
Given there is the supplier with name 'supplier-s32u681s1'
And there is the subCategory with name 'defaultSubCategory-s32u6812' related to group named 'defaultGroup-s32u6812' and category named 'defaultCategory-s32u6812'
Given there is the product with 'name-326812' name, '326812' barcode, 'unit' type, '100' purchasePrice of group named 'defaultGroup-s32u6812', category named 'defaultCategory-s32u6812', subcategory named 'defaultSubCategory-s32u6812'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-326812 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '5' on the invoice product with name 'name-326812'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-326812 | шт. | 5,0 | 100,00 | 500,00 руб. | 0,00 руб. |
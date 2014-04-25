Meta:
@sprint_32
@us_68.1
@invoice

Scenario: Invoice product deletion

Meta:
@id_
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the subCategory with name 'defaultSubCategory-s32u681' related to group named 'defaultGroup-s32u681' and category named 'defaultCategory-s32u681'
And the user sets subCategory 'defaultSubCategory-s32u681' mark up with max '10' and min '0' values

Given there is the product with 'name-32681' name, '32681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'
And there is the product with 'name-326811' name, '326811' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given there is the supplier with name 'SupplierInvoiceDeletionTest'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | SupplierInvoiceDeletionTest |
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

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,0 | 100,00 | 500,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 500,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-326811 |
Then the user waits for the invoice product edition preloader finish

When the user inputs quantity '5' on the invoice product with name 'name-326811'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice total sum is 'Итого: 1 000,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-32681 | шт. | 5,0 | 100,00 | 500,00 руб. | 0,00 руб. |
| name-326811 | шт. | 5,0 | 100,00 | 500,00 руб. | 0,00 руб. |

And the user checks the invoice total sum is 'Итого: 1 000,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

When the user clicks on the invoice product by name 'name-32681'
And the user clicks on delete icon and deletes invoice product with name 'name-32681'

Then the user checks the invoice products list do not contain product with name 'name-32681'
And the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-326811 | шт. | 5,0 | 100,00 | 500,00 руб. | 0,00 руб. |

And the user checks the invoice total sum is 'Итого: 500,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user checks the invoice products list do not contain product with name 'name-32681'
And the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-326811 | шт. | 5,0 | 100,00 | 500,00 руб. | 0,00 руб. |

And the user checks the invoice total sum is 'Итого: 500,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

Scenario: Invoice Product deletion cancel

Meta:
@id_

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

Then the user checks the invoice total sum is 'Итого: 100,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user clicks on invoice product in last created invoice

When the user clicks on delete icon and deletes last added invoice product

Then the user checks the invoice products list do not contain last added product

Then the user checks the invoice total sum is 'Итого: 0,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

When the user clicks the invoice cancel link button

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user checks the invoice total sum is 'Итого: 100,00 руб.'
And the user checks the invoice vat sum is 'НДС: 0,00 руб.'

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 руб. | 0,00 руб. |
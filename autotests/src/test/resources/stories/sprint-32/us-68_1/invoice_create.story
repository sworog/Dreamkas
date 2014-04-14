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
              precondition/sprint-31/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-31/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

When the user clicks the menu invoices item
And the user clicks the create invoice link on order page menu navigation

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-31681 |
And the user inputs quantity '5' on the invoice product with name 'name-31681'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '110' on the invoice product with name 'name-31681'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-31681 | шт. | 5,0 | 110,00 | 550,00 | 56,78 |

And the user checks the invoice total sum is 'Итого: 550,00 руб'
And the user checks the invoice vat sum is 'Итого: 550,00 руб'

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

!--may be the better scenario will be click on search result and verify
When the user clicks on the search result invoice with sku '10001'

Then the user checks stored values on invoice page

Then the user checks the invoice products list contains exact entries
| name | quantity | units | price | totalSum | vatSum |
| name-31681 | шт. | 5,0 | 110,00 | 550,00 | 56,78 |

Scenario: Invoice create with three products

Meta:
@id_
@invoice
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-31/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-31/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given there is the product with 'name-3068101' name, '3068101' sku, '30681' barcode, 'liter' units, '78.90' purchasePrice of group named 'defaultGroup-s31u681', category named 'defaultCategory-s31u681', subcategory named 'defaultSubCategory-s31u681'
And there is the product with 'name-3068102' name, '3068102' sku, '30681' barcode, 'kg' units, '56.78' purchasePrice of group named 'defaultGroup-s31u681', category named 'defaultCategory-s31u681', subcategory named 'defaultSubCategory-s31u681'

Given the user opens the authorization page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

When the user clicks the menu invoices item
And the user clicks the create invoice link on order page menu navigation

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s31u681s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-30681 |
And the user inputs quantity '5,7' on the invoice product with name 'name-30681'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '110' on the invoice product with name 'name-31681'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-31681 | шт. | 5,7 | 110,00 | 627,00 | 56,78 |

Then the user checks the order total sum is 'Итого: 627,00 руб'
And the user checks the invoice vat sum is 'Итого: 56,78 руб'

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-3068101 |
And the user inputs quantity '5,67' on the invoice product with name 'name-3068101'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '123,67' on the invoice product with name 'name-31681'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-31681 | шт. | 5,7 | 110,00 | 627,00 | 56,78 |
| name-3068101 | л | 5,67 | 123,67 | 701,21 | 56,78 |

Then the user checks the order total sum is 'Итого: 1 328,21 руб'
And the user checks the invoice vat sum is 'Итого: 56,78 руб'

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-3068102 |
And the user inputs quantity '45,789' on the invoice product with name 'name-3068102'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-31681 | шт. | 5,7 | 110,00 | 627,00 | 56,78 |
| name-3068101 | л | 5,67 | 123,67 | 701,21 | 56,78 |
| name-3068102 | кг | 45,789 | 56,78 | 2 599,90 | 56,78 |

And the user checks the order total sum is 'Итого: 3 928,11 руб'
And the user checks the invoice vat sum is 'Итого: 56,78 руб'

When the user clicks the save order button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

!--may be the better scenario will be click on search result and verify
When the user clicks on the search result invoice with sku '10001'

Then the user checks stored values on invoice page

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-31681 | шт. | 5,7 | 110,00 | 627,00 | 56,78 |
| name-3068101 | л | 5,67 | 123,67 | 701,21 | 56,78 |
| name-3068102 | кг | 45,789 | 56,78 | 2 599,90 | 56,78 |

And the user checks the order total sum is 'Итого: 3 928,11 руб'
And the user checks the invoice vat sum is 'Итого: 56,78 руб'

Scenario: Verify autocomplete invoice product with no price is choosen

Meta:
@id_

Given there is the user with name 'departmentManager-s31u681', position 'departmentManager-s31u681', username 'departmentManager-s31u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u681' managed by department manager named 'departmentManager-s31u681'
Given there is the supplier with name 'supplier-s31u681s1'
And there is the subCategory with name 'defaultSubCategory-s31u6811' related to group named 'defaultGroup-s31u6811' and category named 'defaultCategory-s31u6811'
Given there is the product with 'name-306811' name, '306811' sku, '306811' barcode, 'unit' units, '' purchasePrice of group named 'defaultGroup-s31u6811', category named 'defaultCategory-s31u6811', subcategory named 'defaultSubCategory-s31u6811'

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-306811 |
And the user inputs quantity '5' on the invoice product with name 'name-306811'
And the user presses 'ENTER' key button

Then the user sees error messages
|error message |
| Цена не может быть равна нулю |

Scenario: Verify autocomplete invoice product with price with no mark up is choosen

Meta:
@id_

Given there is the user with name 'departmentManager-s31u681', position 'departmentManager-s31u681', username 'departmentManager-s31u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s31u681' managed by department manager named 'departmentManager-s31u681'
Given there is the supplier with name 'supplier-s31u681s1'
And there is the subCategory with name 'defaultSubCategory-s31u6812' related to group named 'defaultGroup-s31u6812' and category named 'defaultCategory-s31u6812'
Given there is the product with 'name-306812' name, '306812' sku, '306812' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s31u6812', category named 'defaultCategory-s31u6812', subcategory named 'defaultSubCategory-s31u6812'

Given the user opens the store 'store-s31u681' invoice create page
And the user logs in using 'departmentManager-s31u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-306812 |
And the user inputs quantity '5' on the order product with name 'name-306812'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-306812 | шт. | 5,0 | 100,00 | 500,00 | 4545 |

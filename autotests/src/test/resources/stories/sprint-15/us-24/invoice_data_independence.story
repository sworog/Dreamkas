Meta:
@sprint_15
@us_24

Narrative:
As заведующим отделом,
I want to чтобы данные в накладной не изменялись при редактировании коммерческой службой товаров
In order to иметь возможность работать с оригинальной версией документа

Scenario: Invoice data independence

Meta:
@smoke
@id_s15u24s1

Given there is the user with name 'departmentManager-s15u24', position 'departmentManager-s15u24', username 'departmentManager-s15u24', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s15u24' managed by department manager named 'departmentManager-s15u24'

Given there is the product with 'name-s15u24' name, 'barcode-s15u24' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-s15u24 |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s15u24'

Given the user navigates to the product with name 'name-s15u24'
And the user logs in as 'commercialManager'

When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| name | name-s15u24 edited |
And the user clicks the create button

Then the user checks the stored input values

When the user logs out

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s15u24' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-s15u24 | шт. | 1,0 | 1,00 | 1,00 руб. | 0,00 руб. |

Scenario: Edited product can be added to invoice

Meta:
@smoke
@id_s15u24s2
@skip

!--В данный момент реализация такого сценария не возможна, потому что накладная шлется сразу и целиком

Given there is the user with name 'departmentManager-s15u24', position 'departmentManager-s15u24', username 'departmentManager-s15u24', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s15u24' managed by department manager named 'departmentManager-s15u24'

Given there is the product with 'name-s15u241' name, 'barcode-s15u241' barcode, 'unit' type, '15' purchasePrice

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-s15u241 |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s15u24'

Given the user navigates to the product with name 'name-s15u241'
And the user logs in as 'commercialManager'

When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| name | name-s15u241 edited |
And the user clicks the create button

Then the user checks the stored input values

When the user logs out

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s15u24' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-s15u241 | шт. | 1,0 | 1,00 | 1,00 руб. | 0,00 руб. |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-s15u241 edited |
Then the user waits for the invoice product edition preloader finish

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-s15u241 | шт. | 1,0 | 1,00 | 1,00 руб. | 0,00 руб. |
| name-s15u241 edited | шт. | 1,0 | 15,00 | 15,00 руб. | 0,00 руб. |
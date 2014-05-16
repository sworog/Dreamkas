Meta:
@sprint_21
@us_46

Narrative:
As a Когда накладная в LH не соответствует реальной,
I want to я хочу найти эту накладную в LH и исправить ошибку,
In order to данные в LH стали правильными

Scenario: Nothing found - Invoice

Meta:
@id_s21u46s1
@smoke

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'

Given the user is on the store 'UIBS-NF' invoice list page
And the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Янехочуискатьнакладныеяхочуплатье'
And the user clicks the invoice search button and starts the search
Then the user checks the form results text is 'Мы не смогли найти накладную с номером Янехочуискатьнакладныеяхочуплатье'

Scenario: Invoice results found by number

Meta:
@id_s21u46s2
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'

Given there is created product with name 'UIBS-name'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | UIBS-name |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-UIBS-NF'

Given the user is on the store 'UIBS-NF' invoice list page
And the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

Then the user checks invoice search results contains exact values
| number |
| 10001 |
And the user asserts invoice search results contains highlighted text '10001' of invoice with number '10001'

Scenario: Invoice results found by supplier invoice number

Meta:
@id_s21u46s3
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'

Given there is created product with name 'UIBS-name'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber999 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | UIBS-name |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-UIBS-NF'

Given the user is on the store 'UIBS-NF' invoice list page
And the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'supplierInvoiceNumber999'
And the user clicks the invoice search button and starts the search

Then the user checks invoice search results contains exact values
| number |
| 10001 |

Scenario: Invoice results found by sku and supplier invoice number

Meta:
@id_s21u46s4

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'

Given there is created product with name 'UIBS-name'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber999 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | UIBS-name |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-UIBS-NF'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | 10001 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | UIBS-name |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-UIBS-NF'

Given the user is on the store 'UIBS-NF' invoice list page
And the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

Then the user checks invoice search results contains exact values
| number |
| 10001 |
| 10002 |
And the user asserts invoice search results contains highlighted text '10001' of invoice with number '10001'

Scenario: Invoice results found by supplier invoice number (case where supplier invoice number is duplicated)

Meta:
@id_s21u46s5

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'

Given there is created product with name 'UIBS-name'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber999 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | UIBS-name |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-UIBS-NF'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber999 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | UIBS-name |
| quantity | 1 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-UIBS-NF'

Given the user is on the store 'UIBS-NF' invoice list page
And the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'supplierInvoiceNumber999'
And the user clicks the invoice search button and starts the search

Then the user checks invoice search results contains exact values
| number |
| 10001 |
| 10002 |


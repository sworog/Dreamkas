Meta:
@sprint_19
@us_44

Narrative:
As a заведующий отделом
I want to просматривать список накладных своего магазина
In order to контролировать процесс приемки

Scenario: Creating invoces in different stores by different users

Meta:
@smoke
@id_s19u44s1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
Given there is the user with name 'departmentManager-SIC-2', position 'departmentManager-SIC-2', username 'departmentManager-SIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And there is the store with number 'SIC-02' managed by department manager named 'departmentManager-SIC-2'

Given there is the product with 'name-SIC' name, 'barcode-SIC' barcode, 'unit' type, '100' purchasePrice of group named 'defaultGroup-SIC', category named 'defaultCategory-SIC', subcategory named 'defaultSubCategory-SIC'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-SIC |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-SIC'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | name-SIC |
| quantity | 1 |
| price | 1 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-SIC-2'


Given the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

Then the user checks invoice search results contains exact values
| number |
| 10001 |

When the user searches invoice by sku or supplier sku '10002'
And the user clicks the invoice search button and starts the search

Then the user checks the form results text is 'Мы не смогли найти накладную с номером 10002'

When the user logs out
Given the user is on the store 'SIC-02' invoice list page
When the user logs in using 'departmentManager-SIC-2' userName and 'lighthouse' password


When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10002'
And the user clicks the invoice search button and starts the search

Then the user checks invoice search results contains exact values
| number |
| 10002 |

When the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

Then the user checks the form results text is 'Мы не смогли найти накладную с номером 10001'

Scenario: Left menu invoices link is visible by departmentManager who has store

Meta:
@id_s19u44s2

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user checks the invoices navigation menu item is visible

Scenario: Invoices link navigation by departmentManager who has store

Meta:
@id_s19u44s3

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Invoices create navigation by departmentManager who has store

Meta:
@id_s19u44s4

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Left menu invoices link is visible by departmentManager who has no store

Meta:
@id_s19u44s5

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user checks the invoices navigation menu item is not visible

Scenario: Invoices create navigation by departmentManager who has no store

Meta:
@id_s19u44s6

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: Invoices link navigation by departmentManager who has no store

Meta:
@id_s19u44s7

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the default store invoice create page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user sees the 403 error
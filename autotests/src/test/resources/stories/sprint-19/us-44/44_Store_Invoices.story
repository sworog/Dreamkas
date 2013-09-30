Meta:
@sprint 19
@us 44

Narrative:
As a заведующий отделом
I want to просматривать список накладных своего магазина
In order to контролировать процесс приемки

Scenario: Store invoice creation

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
When the user clicks the create button on the invoice list page
And the user inputs '654321' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'ОАЭ Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Васильев' in the invoice 'accepter' field
And the user inputs 'ООО23' in the invoice 'legalEntity' field
And the user inputs '799' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
And the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with '654321' sku is present
And the user checks the invoice with '654321' sku has 'acceptanceDate' equal to '02.04.2013 16:23'
And the user checks the invoice with '654321' sku has 'supplier' equal to 'ОАЭ Поставщик'
And the user checks the invoice with '654321' sku has 'sumTotal' equal to ''
And the user checks the invoice with '654321' sku has 'accepter' equal to 'Иван Петрович Васильев'

Scenario: Creating invoces in different stores by different users

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
Given there is the user with name 'departmentManager-SIC-2', position 'departmentManager-SIC-2', username 'departmentManager-SIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And there is the store with number 'SIC-02' managed by department manager named 'departmentManager-SIC-2'
And there is the invoice with sku 'CIIDSBDU-1' in the store with number 'SIC-01' ruled by department manager with name 'departmentManager-SIC'
And there is the invoice with sku 'CIIDSBDU-2' in the store with number 'SIC-02' ruled by department manager with name 'departmentManager-SIC-2'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user checks the invoice with 'CIIDSBDU-1' sku is present
Then the user checks the invoice with 'CIIDSBDU-2' sku is not present
When the user logs out
Given the user is on the invoice list page
When the user logs in using 'departmentManager-SIC-2' userName and 'lighthouse' password
Then the user checks the invoice with 'CIIDSBDU-2' sku is present
Then the user checks the invoice with 'CIIDSBDU-1' sku is not present
When the user logs out

Scenario: Left menu invoices link is visible by departmentManager who has store

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user opens the authorization page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user checks the dashboard link to 'invoices' section is present

Scenario: Invoices link navigation by departmentManager who has store

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Invoices create navigation by departmentManager who has store

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the invoice create page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Left menu invoices link is visible by departmentManager who has no store

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user checks the dashboard link to 'invoices' section is not present

Scenario: Invoices create navigation by departmentManager who has no store

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: Invoices link navigation by departmentManager who has no store

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user is on the invoice create page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user sees the 403 error
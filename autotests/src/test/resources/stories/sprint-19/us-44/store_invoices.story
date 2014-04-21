Meta:
@sprint_19
@us_44

Narrative:
As a заведующий отделом
I want to просматривать список накладных своего магазина
In order to контролировать процесс приемки

Scenario: Creating invoces in different stores by different users

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
Given there is the user with name 'departmentManager-SIC-2', position 'departmentManager-SIC-2', username 'departmentManager-SIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And there is the store with number 'SIC-02' managed by department manager named 'departmentManager-SIC-2'
And there is the invoice with sku 'CIIDSBDU-1' in the store with number 'SIC-01' ruled by department manager with name 'departmentManager-SIC'
And there is the invoice with sku 'CIIDSBDU-2' in the store with number 'SIC-02' ruled by department manager with name 'departmentManager-SIC-2'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user checks the invoice with 'CIIDSBDU-1' sku is present
Then the user checks the invoice with 'CIIDSBDU-2' sku is not present
When the user logs out
Given the user is on the store 'SIC-02' invoice list page
When the user logs in using 'departmentManager-SIC-2' userName and 'lighthouse' password
Then the user checks the invoice with 'CIIDSBDU-2' sku is present
Then the user checks the invoice with 'CIIDSBDU-1' sku is not present
When the user logs out

Scenario: Left menu invoices link is visible by departmentManager who has store

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user checks the invoices navigation menu item is visible

Scenario: Invoices link navigation by departmentManager who has store

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Invoices create navigation by departmentManager who has store

Given there is the user with name 'departmentManager-SIC', position 'departmentManager-SIC', username 'departmentManager-SIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SIC-01' managed by department manager named 'departmentManager-SIC'
And the user is on the store 'SIC-01' invoice list page
When the user logs in using 'departmentManager-SIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Left menu invoices link is visible by departmentManager who has no store

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user checks the invoices navigation menu item is not visible

Scenario: Invoices create navigation by departmentManager who has no store

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user is on the invoice list page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: Invoices link navigation by departmentManager who has no store

Given there is the user with name 'departmentManager-SIC-3', position 'departmentManager-SIC-3', username 'departmentManager-SIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the default store invoice create page
When the user logs in using 'departmentManager-SIC-3' userName and 'lighthouse' password
Then the user sees the 403 error
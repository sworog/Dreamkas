Meta:
@sprint_19
@us_45

Narrative:
As a заведующим отделом
I want to просматривать список списаний своего магазина
In order to контролировать процесс списания

Scenario: Store writeOffs creation

Meta:
@smoke

Given there is the user with name 'departmentManager-DIC', position 'departmentManager-DIC', username 'departmentManager-DIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'DIC-01' managed by department manager named 'departmentManager-DIC'
And there is the product with 'WriteOff-wolc-01' name, 'WriteOff-wolc-01' barcode, 'unit' type, '15' purchasePrice
And the user opens write off list page
When the user logs in using 'departmentManager-DIC' userName and 'lighthouse' password
When the user creates write off from write off list page
And the user inputs 'WriteOff-wolc-01' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-wolc-01' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Given the user opens write off list page
Then the user checks the write off with 'WriteOff-wolc-01' is present on write off list page
And the user checks the product with 'WriteOff-wolc-01' sku has 'writeOff list page date' element equal to '24.10.2012' on write off list page
And the user checks the product with 'WriteOff-wolc-01' sku has 'writeOff list page number' element equal to 'WriteOff-wolc-01' on write off list page
And the user checks the product with 'WriteOff-wolc-01' sku has 'writeOff list page sumTotal' element equal to '150' on write off list page

Scenario: Creating writeOffs in different stores by different users

Meta:
@smoke

Given there is the user with name 'departmentManager-DIC', position 'departmentManager-DIC', username 'departmentManager-DIC', password 'lighthouse', role 'departmentManager'
And there is the user with name 'departmentManager-DIC-2', position 'departmentManager-DIC-2', username 'departmentManager-DIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'DIC-01' managed by department manager named 'departmentManager-DIC'
And there is the store with number 'DIC-02' managed by department manager named 'departmentManager-DIC-2'
And there is the write off with sku 'CWIDSBDU-1' in the store with number 'DIC-01' ruled by user with name 'departmentManager-DIC'
And there is the write off with sku 'CWIDSBDU-2' in the store with number 'DIC-02' ruled by user with name 'departmentManager-DIC-2'
And the user opens write off list page
When the user logs in using 'departmentManager-DIC' userName and 'lighthouse' password
Then the user checks the write off with 'CWIDSBDU-1' is present on write off list page
Then the user checks the write off with 'CWIDSBDU-2' is not present on write off list page
When the user logs out
Given the user opens write off list page
When the user logs in using 'departmentManager-DIC-2' userName and 'lighthouse' password
Then the user checks the write off with 'CWIDSBDU-2' is present on write off list page
Then the user checks the write off with 'CWIDSBDU-1' is not present on write off list page
When the user logs out

Scenario: Department manager dont have rights to see writeOff of other store

Given there is the user with name 'departmentManager-DIC', position 'departmentManager-DIC', username 'departmentManager-DIC', password 'lighthouse', role 'departmentManager'
And there is the user with name 'departmentManager-DIC-2', position 'departmentManager-DIC-2', username 'departmentManager-DIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'DIC-01' managed by department manager named 'departmentManager-DIC'
And there is the store with number 'DIC-02' managed by department manager named 'departmentManager-DIC-2'
And there is the write off with sku 'CWIDSBDU-1' in the store with number 'DIC-01' ruled by user with name 'departmentManager-DIC'
Given the user navigates to the write off with number 'CWIDSBDU-1'
When the user logs in using 'departmentManager-DIC-2' userName and 'lighthouse' password
Then the user sees the 404 error

Scenario: Left menu writeOffs link is visible by departmentManager who has store

Given there is the user with name 'departmentManager-DIC', position 'departmentManager-DIC', username 'departmentManager-DIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'DIC-01' managed by department manager named 'departmentManager-DIC'
And the user opens the authorization page
When the user logs in using 'departmentManager-DIC' userName and 'lighthouse' password
Then the user checks the writeOffs navigation menu item is visible

Scenario: WriteOffs link navigation by departmentManager who has store

Given there is the user with name 'departmentManager-DIC', position 'departmentManager-DIC', username 'departmentManager-DIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'DIC-01' managed by department manager named 'departmentManager-DIC'
And the user opens write off list page
When the user logs in using 'departmentManager-DIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: WriteOffs create navigation by departmentManager who has store

Given there is the user with name 'departmentManager-DIC', position 'departmentManager-DIC', username 'departmentManager-DIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'DIC-01' managed by department manager named 'departmentManager-DIC'
And the user opens the write off create page
When the user logs in using 'departmentManager-DIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Left menu writeOffs link is visible by departmentManager who has no store

Given there is the user with name 'departmentManager-DIC-3', position 'departmentManager-DIC-3', username 'departmentManager-DIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
When the user logs in using 'departmentManager-DIC-3' userName and 'lighthouse' password
Then the user checks the writeOffs navigation menu item is not visible

Scenario: WriteOffs create navigation by departmentManager who has no store

Given there is the user with name 'departmentManager-DIC-3', position 'departmentManager-DIC-3', username 'departmentManager-DIC-3', password 'lighthouse', role 'departmentManager'
And the user opens write off list page
When the user logs in using 'departmentManager-DIC-3' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: WriteOffs link navigation by departmentManager who has no store

Given there is the user with name 'departmentManager-DIC-3', position 'departmentManager-DIC-3', username 'departmentManager-DIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the write off create page
When the user logs in using 'departmentManager-DIC-3' userName and 'lighthouse' password
Then the user sees the 403 error
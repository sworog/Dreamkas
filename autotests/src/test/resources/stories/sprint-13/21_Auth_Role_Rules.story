21 Разграничение прав доступа пользователя

Narrative:
Как пользователь,
Я хочу после авторизациии в системе работать только со своими данными и функционалом,
Чтобы фокусироваться на достижении своих целей


Scenario: administrator role username checking
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks that authorized is 'watchman' user
When the user logs out

Scenario: commercialManager role username checking
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks that authorized is 'commercialManager' user
When the user logs out

Scenario: storeManager role username checking
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks that authorized is 'storeManager' user
When the user logs out

Scenario: departmentManager role username checking
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks that authorized is 'departmentManager' user
When the user logs out

Scenario: administrator role valid rules - dashboard links - users
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'users' section is present
When the user logs out

Scenario: commercialManager role valid rules - dashboard links - products
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'products' section is present
When the user logs out

Scenario: commercialManager role valid rules - dashboard links - catalog
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'catalog' section is present
When the user logs out

Scenario: departmentManager role valid rules - dashboard links - invoices
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'invoices' section is present
When the user logs out

Scenario: departmentManager role valid rules - dashboard links - products
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'products' section is present
When the user logs out

Scenario: departmentManager role valid rules - dashboard links - balance
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'balance' section is present
When the user logs out

Scenario: departmentManager role valid rules - dashboard links - writeOffs
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'writeOffs' section is present
When the user logs out

Scenario: departmentManager role valid rules - product card view

Scenario: administrator role invalid rules - dashboard links - products
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'products' section is not present
When the user logs out

Scenario: administrator role invalid rules - dashboard links - catalog
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'catalog' section is not present
When the user logs out

Scenario: administrator role invalid rules - dashboard links - invoices
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'invoices' section is not present
When the user logs out

Scenario: administrator role invalid rules - dashboard links - balance
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'balance' section is not present
When the user logs out

Scenario: administrator role invalid rules - dashboard links - writeOffs
Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'writeOffs' section is not present
When the user logs out

Scenario: commercial manager role invalid rules - dashboard links - invoices
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'invoices' section is not present
When the user logs out

Scenario: commercial manager role invalid rules - dashboard links - balance
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'balance' section is not present
When the user logs out

Scenario: commercial manager role invalid rules - dashboard links - writeOffs
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'writeOffs' section is not present
When the user logs out

Scenario: commercial manager role invalid rules - dashboard links - users
Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'users' section is not present
When the user logs out

Scenario: storeManager role invalid rules - dashboard links - users
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'users' section is not present
When the user logs out

Scenario: storeManager role invalid rules - dashboard links - products
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'users' section is not present
When the user logs out

Scenario: storeManager role invalid rules - dashboard links - catalog
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'catalog' section is not present
When the user logs out

Scenario: storeManager role invalid rules - dashboard links - invoices
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'invoices' section is not present
When the user logs out

Scenario: storeManager role invalid rules - dashboard links - balance
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'balance' section is not present
When the user logs out

Scenario: storeManager role invalid rules - dashboard links - writeOffs
Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'writeOffs' section is not present
When the user logs out

Scenario: departmentManager role invalid rules - dashboard links - users
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'users' section is not present
When the user logs out

Scenario: departmentManager role invalid rules - dashboard links - catalog
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'catalog' section is not present
When the user logs out

Scenario: administrator role valid rules - simple user scenario from dashboard - user creation
Given the user opens the authorization page
And the user logs in as 'watchman'
When user opens the dashboard 'users' section
And the user clicks the create new user button from users list page
And the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя11 |
| position | Позиция22 |
| username | createfromuserslistpage9999 |
| password | Пароль11 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'createfromuserslistpage9999' username is present
When the user logs out

Scenario: commercialManager role valid rules - simple user scenario from dashboard - product creation
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'products' section
And the user creates new product from product list page
And the user inputs 'assps' in 'name' field
And the user inputs '12356' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'assps123' in 'sku' field
And the user clicks the create button
Then the user checks the product with 'assps123' sku is present
When the user logs out

Scenario: commercialManager role valid rules - simple user scenario from dashboard - class creation
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'catalog' section
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GcFcP1234567'
Then the user checks the class with 'GcFcP1234567' name is present
When the user clicks on the class name 'GcFcP1234567'
And the user creates new group with name 'First group create1234567'
And the user clicks on end edition link and ends the edition
Then the user checks the group with 'First group create1234567' name is present
When user opens the dashboard 'catalog' section
Then the user checks the group with 'First group create1234567' name is related to class 'GcFcP1234567'
When the user logs out

Scenario: departmentManager role valid rules - simple user scenario from dashboard - invoice
Given there is the product with 'IFBKG-1199' name, 'IFBKG-1199' sku, 'IFBKG-1199' barcode
And the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'invoices' section
And the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IFBKG-1199' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user inputs 'IFBKG-1199' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
When user opens the dashboard 'invoices' section
Then the user checks the invoice with 'Invoice-IFBKG-1199' sku is present
When the user open the invoice card with 'Invoice-IFBKG-1199' sku
Then the user checks invoice 'head' elements  values
| elementName | expectedValue |
| sku | Invoice-IFBKG-1199 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBKG-1199' sku has values
| elementName | expectedValue |
| productName | IFBKG-1199 |
| productSku | IFBKG-1199 |
| productBarcode | IFBKG-1199 |
| productUnits | кг |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | expectedValue |
| totalProducts | 1 |
| totalSum | 1 |
When the user logs out

Scenario: departmentManager role valid rules - simple user scenario from dashboard - writeOff
Given there is the product with 'WriteOff-ProductName999' name, 'WriteOff-ProductSku999' sku, 'WriteOff-ProductBarCode999' barcode, 'liter' units, '15' purchasePrice
And the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'balance' section
Then the user checks the product with 'WriteOff-ProductSku999' sku has 'amounts amount' element equal to '0' on amounts page
When user opens the dashboard 'writeOffs' section
And the user creates write off from write off list page
And the user inputs 'WriteOff Number-1999' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName999' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | expectedValue |
| writeOff number review | WriteOff Number-1999 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku999' sku is present
And the user checks the product with 'WriteOff-ProductSku999' sku has elements on the write off page
| elementName | expectedValue |
| writeOff product name review | WriteOff-ProductName999 |
| writeOff product sku review | WriteOff-ProductSku999 |
| writeOff product barCode review | WriteOff-ProductBarCode999 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
Then the user checks write off elements values
| elementName | expectedValue |
| totalProducts | 1 |
| totalSum | 150 |
When user opens the dashboard 'balance' section
Then the user checks the product with 'WriteOff-ProductSku999' sku has 'amounts amount' element equal to '-10' on amounts page
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from product create page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the product create page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from product list page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the product list page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from catalog page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens catalog page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from ivoice list page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the invoice list page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from ivoice create page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the invoice create page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from amount list page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens amount list page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from write off create page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens the write off create page
Then the user sees the 403 error
When the user logs out

Scenario: administrator role invalid rules - unauthorised access from write off list page link
Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens write off list page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from users page link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens create new user page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from users create page link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the users list page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from invoices link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the invoice list page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from invoices create page link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the invoice create page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from balance link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens amount list page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from writeOffs link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens write off list page
Then the user sees the 403 error
When the user logs out

Scenario: commercialManager role invalid rules - unauthorised access from writeOffs create page link
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens the write off create page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from users page link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens create new user page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from users create page link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the users list page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from products page link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the product list page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from products create page link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the product create page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from invoices link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the invoice list page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from invoices create page link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the invoice create page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from balance link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens amount list page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from writeOffs link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens write off list page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from writeOffs create page link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens the write off create page
Then the user sees the 403 error
When the user logs out

Scenario: storeManager role invalid rules - unauthorised access from catalog link
Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens catalog page
Then the user sees the 403 error
When the user logs out

Scenario: departmentManager role invalid rules - unauthorised access from users page link
Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user opens create new user page
Then the user sees the 403 error
When the user logs out

Scenario: departmentManager role invalid rules - unauthorised access from users create page link
Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user is on the users list page
Then the user sees the 403 error
When the user logs out

Scenario: departmentManager role invalid rules - unauthorised access from products create page link
Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user is on the product create page
Then the user sees the 403 error
When the user logs out

Scenario: departmentManager role invalid rules - unauthorised access from catalog link
Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user opens catalog page
Then the user sees the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to products list
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'products' section
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to products card
Given there is the product with 'IFBKG-119999' name, 'IFBKG-119999' sku, 'IFBKG-119999' barcode
And the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'products' section
When the user open the product card with 'IFBKG-119999' sku
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to balance
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'balance' section
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to invoice list
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'invoices' section
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to invoice create
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'invoices' section
And the user clicks the create button on the invoice list page
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to writeOffs list
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'writeOffs' section
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager role valid rules - authorised access to writeOffs create
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'writeOffs' section
And the user creates write off from write off list page
Then the user dont see the 403 error
When the user logs out

Scenario: commercialManager role valid rules - authorised access to product list
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'products' section
Then the user dont see the 403 error
When the user logs out

Scenario: commercialManager role valid rules - authorised access to product create
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'products' section
And the user creates new product from product list page
Then the user dont see the 403 error
When the user logs out

Scenario: commercialManager role valid rules - authorised access to catalog
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'catalog' section
Then the user dont see the 403 error
When the user logs out

Scenario: administrator role valid rules - authorised access to users list
Given the user opens the authorization page
And the user logs in as 'watchman'
When user opens the dashboard 'users' section
Then the user dont see the 403 error
When the user logs out

Scenario: administrator role valid rules - authorised access to users create
Given the user opens the authorization page
And the user logs in as 'watchman'
When user opens the dashboard 'users' section
And the user clicks the create new user button from users list page
Then the user dont see the 403 error
When the user logs out

Scenario: departmentManager - no edit button for products card
Given there is the product with 'IFBKG-119999' name, 'IFBKG-119999' sku, 'IFBKG-119999' barcode
And the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'products' section
When the user open the product card with 'IFBKG-119999' sku
Then the user sees no edit product button
When the user logs out

Scenario: departmentManager - no create button for products list
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'products' section
Then the user sees no create new product button
When the user logs out

Scenario: administrator role - user card view from dashboard
Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | expectedValue |
| name | watchman |
| position | Системный администратор |
| username | watchman |
| role | Системный администратор |
When the user logs out

Scenario: administrator role - user card view from dashboard - edit button is present
Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens dashboard user card
Then the user sees user card edit button
When the user logs out

Scenario: administrator role - user card view from dashboard - users list link is present
Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens dashboard user card
Then the user sees user card link to users list
When the user logs out

Scenario: departmentManager role - user card view from dashboard
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | expectedValue |
| name | departmentManager |
| position | Заведующий отделом |
| username | departmentManager |
| role | Заведующий отделом |
When the user logs out

Scenario: departmentManager role - user card view from dashboard - edit button is not present
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens dashboard user card
Then the user sees no user card edit button
When the user logs out

Scenario: departmentManager role - user card view from dashboard - users list link is not present
Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens dashboard user card
Then the user sees no user card link to users list
When the user logs out

Scenario: commercialManager role - user card view from dashboard
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | expectedValue |
| name | commercialManager |
| position | Коммерческий директор сети |
| username | commercialManager |
| role | Коммерческий директор сети |
When the user logs out

Scenario: commercialManager role - user card view from dashboard - edit button is not present
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens dashboard user card
Then the user sees no user card edit button
When the user logs out

Scenario: commercialManager role - user card view from dashboard - users list link is not present
Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens dashboard user card
Then the user sees no user card link to users list
When the user logs out

Scenario: storeManager role - user card view from dashboard
Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | expectedValue |
| name | storeManager |
| position | Директор магазина |
| username | storeManager |
| role | Директор магазина |
When the user logs out

Scenario: storeManager role - user card view from dashboard - edit button is not present
Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens dashboard user card
Then the user sees no user card edit button
When the user logs out

Scenario: storeManager role - user card view from dashboard - users list link is not present
Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens dashboard user card
Then the user sees no user card link to users list
When the user logs out

21 Разграничение прав доступа пользователя

Narrative:
In order to фокусироваться на достижении своих целей
As a пользователь
I want to после авторизациии в системе работать только со своими данными и функционалом,

Meta:
@sprint 13
@us 21

Scenario: Administrator role username checking

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks that authorized is 'watchman' user

Scenario: CommercialManager role username checking

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks that authorized is 'commercialManager' user

Scenario: StoreManager role username checking

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks that authorized is 'storeManager' user

Scenario: DepartmentManager role username checking

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks that authorized is 'departmentManager' user

Scenario: Administrator role valid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'users' section is present

Scenario: CommercialManager role valid rules - dashboard links - catalog

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'catalog' section is present

Scenario: DepartmentManager role valid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'invoices' section is present

Scenario: DepartmentManager role valid rules - dashboard links - balance

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'balance' section is present

Scenario: DepartmentManager role valid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'writeOffs' section is present

Scenario: Administrator role invalid rules - dashboard links - catalog

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'catalog' section is not present

Scenario: Administrator role invalid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'invoices' section is not present

Scenario: Administrator role invalid rules - dashboard links - balance

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'balance' section is not present

Scenario: Administrator role invalid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'writeOffs' section is not present

Scenario: Commercial manager role invalid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'invoices' section is not present

Scenario: Commercial manager role invalid rules - dashboard links - balance

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'balance' section is present

Scenario: Commercial manager role invalid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'writeOffs' section is not present

Scenario: Commercial manager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'users' section is not present

Scenario: StoreManager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'users' section is not present

Scenario: StoreManager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'users' section is not present

Scenario: StoreManager role invalid rules - dashboard links - catalog

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'catalog' section is not present

Scenario: StoreManager role invalid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'invoices' section is not present

Scenario: StoreManager role invalid rules - dashboard links - balance

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'balance' section is not present

Scenario: StoreManager role invalid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'writeOffs' section is not present

Scenario: DepartmentManager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'users' section is not present

Scenario: Administrator role valid rules - simple user scenario from dashboard - user creation

Given the user opens the authorization page
And the user logs in as 'watchman'
When user opens the dashboard 'users' section
And the user clicks the create new user button from users list page
And the user inputs values in the user page element fields
| elementName | value |
| name | Имя11 |
| position | Позиция22 |
| username | createfromuserslistpage9999 |
| password | Пароль11 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'createfromuserslistpage9999' username is present

Scenario: CommercialManager role valid rules - simple user scenario from dashboard - group creation

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'catalog' section
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GcFcP1234567'
Then the user checks the group with 'GcFcP1234567' name is present
When the user clicks on the group name 'GcFcP1234567'
And the user clicks create new category button
And the user inputs 'First category create1234567' in 'name' field of pop up
And the user clicks the create new category button in pop up
And the user clicks on end edition link and ends the edition
Then the user checks the category with 'First category create1234567' name is present
When user opens the dashboard 'catalog' section
Then the user checks the category with 'First category create1234567' name is related to group 'GcFcP1234567'

Scenario: DepartmentManager role valid rules - simple user scenario from dashboard - invoice

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
| elementName | value |
| sku | Invoice-IFBKG-1199 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBKG-1199' sku has values
| elementName | value |
| productName | IFBKG-1199 |
| productSku | IFBKG-1199 |
| productBarcode | IFBKG-1199 |
| productUnits | кг |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 1 |

Scenario: DepartmentManager role valid rules - simple user scenario from dashboard - writeOff

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
| elementName | value |
| writeOff number review | WriteOff Number-1999 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku999' sku is present
And the user checks the product with 'WriteOff-ProductSku999' sku has elements on the write off page
| elementName | value |
| writeOff product name review | WriteOff-ProductName999 |
| writeOff product sku review | WriteOff-ProductSku999 |
| writeOff product barCode review | WriteOff-ProductBarCode999 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |
When user opens the dashboard 'balance' section
Then the user checks the product with 'WriteOff-ProductSku999' sku has 'amounts amount' element equal to '-10' on amounts page

Scenario: Administrator role invalid rules - unauthorised access from product create page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the product create page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from product list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the product list page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from catalog page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens catalog page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from ivoice list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the invoice list page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from ivoice create page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the invoice create page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from amount list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens amount list page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from write off create page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens the write off create page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from write off list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens write off list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from users page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens create new user page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from users create page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the users list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from invoices link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the invoice list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from invoices create page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the invoice create page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from balance link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens amount list page
Then the user dont see the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from writeOffs link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens write off list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from writeOffs create page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens the write off create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from users page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens create new user page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from users create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the users list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from products page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the product list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from products create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the product create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from invoices link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the invoice list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from invoices create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the invoice create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from balance link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens amount list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from writeOffs link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens write off list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from writeOffs create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens the write off create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from catalog link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens catalog page
Then the user sees the 403 error

Scenario: DepartmentManager role invalid rules - unauthorised access from users page link

Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user opens create new user page
Then the user sees the 403 error

Scenario: DepartmentManager role invalid rules - unauthorised access from users create page link

Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user is on the users list page
Then the user sees the 403 error

Scenario: DepartmentManager role invalid rules - unauthorised access from products create page link

Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user is on the product create page
Then the user sees the 403 error

Scenario: DepartmentManager role valid rules - authorised access to balance

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'balance' section
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to invoice list

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'invoices' section
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to invoice create

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'invoices' section
And the user clicks the create button on the invoice list page
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to writeOffs list

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'writeOffs' section
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to writeOffs create

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'writeOffs' section
And the user creates write off from write off list page
Then the user dont see the 403 error

Scenario: CommercialManager role valid rules - authorised access to catalog

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When user opens the dashboard 'catalog' section
Then the user dont see the 403 error

Scenario: Administrator role valid rules - authorised access to users list

Given the user opens the authorization page
And the user logs in as 'watchman'
When user opens the dashboard 'users' section
Then the user dont see the 403 error

Scenario: Administrator role valid rules - authorised access to users create

Given the user opens the authorization page
And the user logs in as 'watchman'
When user opens the dashboard 'users' section
And the user clicks the create new user button from users list page
Then the user dont see the 403 error

Scenario: DepartmentManager - no edit button for products card

Given there is the product with 'IFBKG-119999' name, 'IFBKG-119999' sku, 'IFBKG-119999' barcode
And the user is on the product list page
And the user logs in as 'departmentManager'
When the user open the product card with 'IFBKG-119999' sku
Then the user sees no edit product button

Scenario: DepartmentManager - no create button for products list

Given the user is on the product list page
And the user logs in as 'departmentManager'
Then the user sees no create new product button

Scenario: Administrator role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | value |
| name | watchman |
| position | Системный администратор |
| username | watchman |
| role | Системный администратор |

Scenario: Administrator role - user card view from dashboard - edit button is present

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens dashboard user card
Then the user sees user card edit button

Scenario: Administrator role - user card view from dashboard - users list link is present

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens dashboard user card
Then the user sees user card link to users list

Scenario: DepartmentManager role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | value |
| name | departmentManager |
| position | Заведующий отделом |
| username | departmentManager |
| role | Заведующий отделом |

Scenario: DepartmentManager role - user card view from dashboard - edit button is not present

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens dashboard user card
Then the user sees no user card edit button

Scenario: DepartmentManager role - user card view from dashboard - users list link is not present

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens dashboard user card
Then the user sees no user card link to users list

Scenario: CommercialManager role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | value |
| name | commercialManager |
| position | Коммерческий директор сети |
| username | commercialManager |
| role | Коммерческий директор сети |

Scenario: CommercialManager role - user card view from dashboard - edit button is not present

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens dashboard user card
Then the user sees no user card edit button

Scenario: CommercialManager role - user card view from dashboard - users list link is not present

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens dashboard user card
Then the user sees no user card link to users list

Scenario: StoreManager role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens dashboard user card
Then the user checks the user page elements values
| elementName | value |
| name | storeManager |
| position | Директор магазина |
| username | storeManager |
| role | Директор магазина |

Scenario: StoreManager role - user card view from dashboard - edit button is not present

Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens dashboard user card
Then the user sees no user card edit button

Scenario: StoreManager role - user card view from dashboard - users list link is not present

Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens dashboard user card
Then the user sees no user card link to users list

Scenario: Regress - No store storeManager get 403 after try to open product page url

Given there is the product with 'storeProductName14' name, 'storeProductSku14' sku, 'storeProductBarCode14' barcode, 'kg' units, '10' purchasePrice of group named 'storeProductsGroup', category named 'storeProductsCategory', subcategory named 'storeProductsSubCategoryOne' with 'nearest100' rounding
And the user navigates to the product with sku 'storeProductSku14'
And the user logs in as 'storeManager'
Then the user sees the 403 error

20 Авторизация пользователя в системе

Narrative:
In order to работать
As a пользователь
I want to авторизироваться в системе

Meta:
@sprint_13
@us_20

Scenario: Authorization invalid password

Given the user opens the authorization page
When the user logs in using 'watchman' userName and '123456' password to check validation
Then the user sees error messages
| error message |
| Неверный логин или пароль |

Scenario: Authorization invalid password, userName

Given the user opens the authorization page
When the user logs in using '123456' userName and '123456' password to check validation
Then the user sees error messages
| error message |
| Неверный логин или пароль |

Scenario: Authorization blank userName, password

Given the user opens the authorization page
When the user logs in using '' userName and '' password to check validation
Then the user sees error messages
| error message |
| Пожалуйста заполните обязательные поля логин и пароль |

Scenario: Authorization blank only userName

Given the user opens the authorization page
When the user logs in using '' userName and '123456' password to check validation
Then the user sees error messages
| error message |
| Пожалуйста заполните обязательные поля логин и пароль |

Scenario: Authorization blank only password

Given the user opens the authorization page
When the user logs in using 'watchman' userName and '' password to check validation
Then the user sees error messages
| error message |
| Пожалуйста заполните обязательные поля логин и пароль |

Scenario: Authorization, log out successfull

Given the user opens the authorization page
When the user logs in using 'watchman' userName and 'lighthouse' password
And the user logs out
Then the user checks the login form is present

Scenario: Unathorization use - product create page

Given the user is on the product create page
Then the user checks the login form is present

Scenario: Unathorization use - product list page

Given the user is on the product list page
Then the user checks the login form is present

Scenario: Unathorization use - catalog page

Given the user opens catalog page
Then the user checks the login form is present

Scenario: Unathorization use - users list page

Given the user is on the users list page
Then the user checks the login form is present

Scenario: Unathorization use - users create page

Given the user opens create new user page
Then the user checks the login form is present

Scenario: Unathorization use - users ivoice list page

Given the user is on the invoice list page
Then the user checks the login form is present

Scenario: Unathorization use - users ivoice create page

Given the user opens the default store invoice create page
Then the user checks the login form is present

Scenario: Unathorization use - amount list page

Given the user opens amount list page
Then the user checks the login form is present

Scenario: Unathorization use - the write off create page

Given the user opens the write off create page
Then the user checks the login form is present

Scenario: Unathorization use - the write off list page

Given the user opens write off list page
Then the user checks the login form is present

Scenario: Authorization successfull - simple product scenarios

Given the user is on the product list page
When the user logs in using 'commercialManager' userName and 'lighthouse' password
And the user creates new product from product list page
And the user inputs 'assps' in 'name' field
And the user inputs '12356' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'assps' in 'sku' field
And the user clicks the create button
Then the user checks the product with 'assps' sku is present

Scenario: Authorization successfull - simple group scenarios

Given the user opens catalog page
When the user logs in using 'commercialManager' userName and 'lighthouse' password
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GcFcP123'
Then the user checks the group with 'GcFcP123' name is present
When the user clicks on the group name 'GcFcP123'
And the user clicks create new category button
And the user inputs 'First category create123' in 'name' field of pop up
And the user clicks the create new category button in pop up
And the user clicks on end edition link and ends the edition
Then the user checks the category with 'First category create123' name is present
Given the user opens catalog page
Then the user checks the category with 'First category create123' name is related to group 'GcFcP123'

Scenario: Authorization successfull - simple invoice scenarios

Given there is the product with 'IFBKG-119' name, 'IFBKG-119' sku, 'IFBKG-119' barcode
And the user is on the invoice list page
When the user logs in using 'departmentManager' userName and 'lighthouse' password
And the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IFBKG-119' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user inputs 'IFBKG-119' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IFBKG-119' sku is present
When the user open the invoice card with 'Invoice-IFBKG-119' sku
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-IFBKG-119 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBKG-119' sku has values
| elementName | value |
| productName | IFBKG-119 |
| productSku | IFBKG-119 |
| productBarcode | IFBKG-119 |
| productUnits | кг |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 1 |

Scenario: Authorization successfull - simple write off scenarios

Given there is the product with 'WriteOff-ProductName99' name, 'WriteOff-ProductSku99' sku, 'WriteOff-ProductBarCode99' barcode, 'liter' units, '15' purchasePrice
And the user opens amount list page
When the user logs in using 'departmentManager' userName and 'lighthouse' password
Then the user checks the product with 'WriteOff-ProductSku99' sku has 'amounts amount' element equal to '0' on amounts page
Given the user opens the write off create page
When the user inputs 'WriteOff Number-199' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName99' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-199 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku99' sku is present
And the user checks the product with 'WriteOff-ProductSku99' sku has elements on the write off page
| elementName | value |
| writeOff product name review | WriteOff-ProductName99 |
| writeOff product sku review | WriteOff-ProductSku99 |
| writeOff product barCode review | WriteOff-ProductBarCode99 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductSku99' sku has 'amounts amount' element equal to '-10' on amounts page

Scenario: Authorization successfull - simple user scenarios

Given the user is on the users list page
When the user logs in using 'watchman' userName and 'lighthouse' password
When the user clicks the create new user button from users list page
And the user inputs values in the user page element fields
| elementName | value |
| name | Имя11 |
| position | Позиция22 |
| username | createfromuserslistpage99 |
| password | Пароль11 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'createfromuserslistpage99' username is present


